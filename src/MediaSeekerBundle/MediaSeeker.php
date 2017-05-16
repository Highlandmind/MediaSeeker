<?php

namespace MediaSeeker;

use MediaSeeker\FileSystem\FileSystemInterface;
use MediaSeeker\Models\Media;

class MediaSeeker
{
    /** @var FileSystemInterface */
    protected $fileSystem;
    /** @var  MediaStore */
    protected $store;

    public function __construct(FileSystemInterface $fileSystem, MediaStore $store)
    {
        $this->fileSystem = $fileSystem;
        $this->store = $store;
    }

    /**
     * @param array $paths
     * @param array $extensions
     * @return array|Media[]
     */
    public function collectMedia(array $paths, array $extensions): array
    {
        $files = $this->findFiles($paths, $extensions);

        $result = [];

        foreach ($files as $file) {
            $result[] = $this->readFileInfo($file);
        }

        return $this->removeDuplicates($result);
    }

    public function removeDuplicates(array $media): array
    {
        $helper = [];
        $resultMedia = [];

        /** @var Media $file */
        foreach ($media as $file) {
            if (isset($helper[$file->getName()])) {
                if ($helper[$file->getName()]->getSize() === $file->getSize()) {
                    continue;
                }

                do {
                    // TODO in extreme cases this may be infinite, try to fix this
                    $file->generateNewName();
                } while (isset($helper[$file->getName()]));
            }

            $helper[$file->getName()] = $file;
            $resultMedia[] = $file;
        }

        return $resultMedia;
    }

    public function organize(array $paths, array $extensions): self
    {
        $files = $this->collectMedia($paths, $extensions);

        /** @var Media $file */
        foreach ($files as $file) {
            $this->store->store($file);
        }

        return $this;
    }

    public function findFiles(array $paths, array $extensions): array
    {
        $files = [];
        foreach ($paths as $path) {
            $files = array_merge($files, $this->fileSystem->findFilesInPath($path, $extensions));
        }

        return array_unique($files);
    }

    public function readFileInfo($file): Media
    {
        $dates = [filemtime($file)];

        if (@exif_imagetype($file) === IMAGETYPE_JPEG) {
            $exifData = exif_read_data($file);

            if (isset($exifData['DateTime'])) {
                $exifDate = $exifData['DateTime'];
                if (is_numeric($exifDate)) {
                    $dates[] = intval($exifDate);
                } else {
                    $time = strtotime($exifDate);
                    if (is_int($time)) {
                        $dates[] = $time;
                    }
                }
            }
        }

        return new Media($file, min($dates), filesize($file));
    }
}