<?php
/**
 * Created by PhpStorm.
 * User: Zonk
 * Date: 14.05.2017
 * Time: 15:24
 */

namespace MediaSeeker\FileSystem;


class FileSystem
{
    public function findFilesInPath($path, array $extensions)
    {
        $filter = new \RecursiveCallbackFilterIterator(
            new \RecursiveDirectoryIterator($path),
            function (\SplFileInfo $current, $key, $iterator) use ($extensions) {
                if ($iterator->hasChildren()) {
                    return true;
                }
                if ($current->isFile()) {
                    $isAcceptableExtension = in_array(strtolower($current->getExtension()), $extensions, true);
                    return $isAcceptableExtension;
                }

                return false;
            }
        );

        $files = [];
        foreach ((new \RecursiveIteratorIterator($filter)) as $info) {
            if ($info->isFile()) {
                $files[] = $info->getPathname();
            }
        }

        return $files;
    }
}