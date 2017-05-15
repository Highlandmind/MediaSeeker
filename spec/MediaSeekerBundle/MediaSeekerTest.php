<?php
/**
 * Created by PhpStorm.
 * User: 236
 * Date: 2017-05-15
 * Time: 09:44
 */

namespace MediaSeekerTest;


use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStreamWrapper;

class MediaSeekerTest extends TestCase
{
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('photos'));
    }

    public function testToTest()
    {
        $this->assertTrue(true);
    }
}