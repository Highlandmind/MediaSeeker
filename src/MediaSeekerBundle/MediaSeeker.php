<?php
/**
 * Created by PhpStorm.
 * User: 236
 * Date: 2017-05-15
 * Time: 09:38
 */

namespace MediaSeeker;

use MediaSeeker\FileSystem\FileSystem;

class MediaSeeker
{
    protected $fileSystem;

    public function __construct(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function findFiles(array $paths, array $extensions): array
    {
        $files = [];
        foreach ($paths as $path) {
            $files = array_merge($files, $this->fileSystem->findFilesInPath(realpath($path), $extensions));
        }

        return array_unique($files);
    }
}