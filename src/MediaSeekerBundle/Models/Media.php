<?php
/**
 * Created by PhpStorm.
 * User: Zonk
 * Date: 14.05.2017
 * Time: 17:09
 */

namespace MediaSeeker\Models;


class Media
{
    private $name;
    private $source;
    private $extension;
    private $timestamp;
    private $type;
    private $nameIterator;
    private $size;

    public function __construct(string $source, $timestamp, int $size)
    {
        $this->nameIterator = 0;
        $this->name = pathinfo($source, PATHINFO_FILENAME);
        $this->extension = pathinfo($source, PATHINFO_EXTENSION);
        $this->source = $source;
        $this->timestamp = $timestamp;
        $this->size = $size;
    }

    public function getSize(): int
    {
        return $this->size;
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
}