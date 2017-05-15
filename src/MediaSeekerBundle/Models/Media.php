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
    private $timestamp;
    private $type;
    private $nameIterator;

    public function __construct(string $name, $timestamp)
    {
        $this->nameIterator = 0;
        $this->name = $name;
        $this->timestamp = $timestamp;
    }

    public function generateNewName(): string
    {
        return $this->name . '_' . sprintf('%02d', $this->nameIterator++);
    }
}