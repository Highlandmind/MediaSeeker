<?php
/**
 * Created by PhpStorm.
 * User: 236
 * Date: 2017-05-15
 * Time: 13:56
 */

namespace MediaSeeker;


use MediaSeeker\Models\Media;

class ImageStore implements MediaStoreInterface
{
    public function store(Media $file)
    {
        // determine new path
        // check if is duplicate
    }
}