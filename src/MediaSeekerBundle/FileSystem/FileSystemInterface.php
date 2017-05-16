<?php
/**
 * Created by PhpStorm.
 * User: 236
 * Date: 2017-05-16
 * Time: 08:57
 */

namespace MediaSeeker\FileSystem;


interface FileSystemInterface
{
    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function copy(string $source, string $destination);

    /**
     * @param $path
     * @param array $extensions
     * @return array
     */
    public function findFilesInPath($path, array $extensions);
}