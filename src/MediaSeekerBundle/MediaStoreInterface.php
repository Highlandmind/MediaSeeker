<?php

namespace MediaSeeker;


use MediaSeeker\Models\Media;

interface MediaStoreInterface
{
    public function store(Media $file);
}