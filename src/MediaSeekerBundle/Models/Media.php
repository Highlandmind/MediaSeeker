<?php

namespace MediaSeeker\Models;


class Media
{
    private const PHOTOS = [
        'jpg',
        'jpeg',
        '3fr',
        'ari',
        'arw',
        'bay',
        'crw',
        'cr2',
        'cap',
        'data',
        'dcs',
        'dcr',
        'dng',
        'drf',
        'eip',
        'erf',
        'fff',
        'iiq',
        'k25',
        'kdc',
        'mdc',
        'mef',
        'mos',
        'mrw',
        'nef',
        'nrw',
        'obm',
        'orf',
        'pef',
        'ptx',
        'pxn',
        'r3d',
        'raf',
        'raw',
        'rwl',
        'rw2',
        'rwz',
        'sr2',
        'srf',
        'srw',
        'tif',
        'x3f'
    ];

    private const VIDEOS = [
        'mts',
        'mp4'
    ];

    private $name;
    private $source;
    private $extension;
    private $timestamp;
    private $nameIterator;
    private $size;

    public function __construct(string $source, int $timestamp, int $size)
    {
        $this->nameIterator = 0;
        $this->extension = pathinfo($source, PATHINFO_EXTENSION);
        $this->source = $source;
        $this->size = $size;
        $this->setTimestamp($timestamp);
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getType(): string
    {
        if ($this->isPhoto()) {
            return 'PHOTO';
        } else if ($this->isVideo()) {
            return 'VIDEO';
        } else {
            return 'UNKNOWN';
        }
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getName(): string
    {
        $index = sprintf('%02d', $this->nameIterator);
        return "{$this->name}_{$index}.{$this->extension}";
    }

    public function generateNewName(): string
    {
        $this->nameIterator++;
        return $this->getName();
    }

    public function getTimestamp(): int {
        return $this->timestamp;
    }

    public function isPhoto()
    {
        return in_array($this->extension, self::PHOTOS, true);
    }

    public function isVideo()
    {
        return in_array($this->extension, self::VIDEOS, true);
    }

    private function setTimestamp(int $timestamp): void {
        $this->timestamp = $timestamp;
        $this->name = date('YmdHis', $timestamp);
    }
}