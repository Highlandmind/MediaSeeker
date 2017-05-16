<?php

namespace MediaSeeker;


use MediaSeeker\FileSystem\FileSystem;
use MediaSeeker\Models\Media;

class ImageStore implements MediaStoreInterface
{
    /** @var FileSystem */
    private $fileSystem;
    private $basePath;

    public function __construct(FileSystem $fileSystem, string $basePath)
    {
        $this->fileSystem = $fileSystem;

        $this->basePath = $basePath;
        if (substr($basePath, -1) !== DIRECTORY_SEPARATOR) {
            $this->basePath = $basePath . DIRECTORY_SEPARATOR;
        }
    }

    public function store(Media $file)
    {
        $time = $file->getTimestamp();
        setlocale(LC_TIME, 'pl_PL');

        $year = date('Y', $time);
        $month = date('m F');

        $finalPath = $this->basePath . $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR . $file->getName();
        $this->fileSystem->copy($file->getSource(), $finalPath);
    }
}