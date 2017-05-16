<?php
namespace MediaSeeker;


use MediaSeeker\Models\Media;

class MediaStore
{
    /** @var  MediaStoreInterface[] */
    private $stores;

    public function __construct()
    {
        $this->stores = [];
    }

    public function store(Media $file)
    {
        if (isset($this->stores[$file->getType()])) {
            $this->stores[$file->getType()]->store($file);
        }
    }

    public function registerStore(string $type, MediaStoreInterface $store): self
    {
        // TODO log warning if store is registered and overrides

        $this->stores[$type] = $store;

        return $this;
    }
}