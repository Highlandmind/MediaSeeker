<?php

namespace MediaSeeker;


use MediaSeeker\FileSystem\FileSystemInterface;
use MediaSeeker\Models\Media;

class ImageStore implements MediaStoreInterface
{
    use StoreTrait;

    /** @var FileSystemInterface */
    private $fileSystem;
    private $basePath;

    public function __construct(FileSystemInterface $fileSystem, string $basePath)
    {
        $this->fileSystem = $fileSystem;

        $this->basePath = $this->preparePath($basePath);
    }

    public function store(Media $file)
    {
        $finalPath = $this->generateDestinationPath($this->basePath, $file) . $file->getName();
        $this->fileSystem->copy($file->getSource(), $finalPath);
    }
}