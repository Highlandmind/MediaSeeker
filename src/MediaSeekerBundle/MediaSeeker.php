<?php

namespace MediaSeeker;

use MediaSeeker\FileSystem\FileSystem;
use MediaSeeker\Models\Media;

class MediaSeeker
{
    protected $fileSystem;

    public function __construct(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param array $paths
     * @param array $extensions
     * @return array|Media[]
     */
    public function collectMedia(array $paths, array $extensions): array {
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

                do  {
                    $file->generateNewName();
                } while (isset($helper[$file->getName()]));
            }

            $helper[$file->getName()] = $file;
            $resultMedia[] = $file;
        }

        return $resultMedia;
    }

    public function organize(array $files)
    {
        $photoStore = new ImageStore($this->fileSystem, 'd:\\test-photos\\');

        /** @var Media $file */
        foreach ($files as $file) {
            if ($file->isPhoto()) {
                $photoStore->store($file);
            } else if ($file->isVideo()) {
                // use video storage
            }
        }
    }

    public function findFiles(array $paths, array $extensions): array
    {
        $files = [];
        foreach ($paths as $path) {
            $files = array_merge($files, $this->fileSystem->findFilesInPath(realpath($path), $extensions));
        }

        return array_unique($files);
    }

    public function readFileInfo($file): Media
    {
        $dates = [filemtime($file)];

        if (exif_imagetype($file) === IMAGETYPE_JPEG) {
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