<?php

namespace MediaSeeker\FileSystem;


class FileSystem implements FileSystemInterface
{
    /**
     * @param $path
     * @param array $extensions
     * @return array
     */
    public function findFilesInPath($path, array $extensions): array
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

    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function copy(string $source, string $destination): boolean
    {
        $dir = pathinfo($destination, PATHINFO_DIRNAME);

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        return copy($source, $destination);
    }
}