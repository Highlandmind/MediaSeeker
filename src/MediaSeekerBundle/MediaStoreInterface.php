<?php
/**
 * Created by PhpStorm.
 * User: 236
 * Date: 2017-05-15
 * Time: 13:56
 */

namespace MediaSeeker;


use MediaSeeker\Models\Media;

interface MediaStoreInterface
{
    public function store(Media $file);
}