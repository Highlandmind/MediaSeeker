<?php
/**
 * Created by PhpStorm.
 * User: 236
 * Date: 2017-05-16
 * Time: 07:39
 */

namespace MediaSeeker;


use MediaSeeker\Models\Media;

trait StoreTrait
{
    protected function preparePath(string $path): string
    {
        if (substr($path, -1) !== DIRECTORY_SEPARATOR) {
            return $path . DIRECTORY_SEPARATOR;
        } else {
            return $path;
        }
    }

    protected function generateDestinationPath(string $basePath, Media $file): string
    {
        $time = $file->getTimestamp();
        setlocale(LC_TIME, 'pl_PL');

        $year = date('Y', $time);
        $month = date('m F', $time);

        $finalPath = $basePath . $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR;

        return $finalPath;
    }
}