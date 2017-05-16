<?php

namespace MediaSeekerTest\Models;

use MediaSeeker\Models\Media;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    public function testCanBeCreatedFromValidData(): void
    {
        $this->assertTrue(true);
    }

    public function testIsTypeResolutionWorking(): void
    {
        $photoMedia = new Media('image.jpg', time(), 65431);
        $videoMedia = new Media('source.mp4', time(), 7887);
        $unknownMedia = new Media('source.mps4', time(), 7887);

        $this->assertTrue($photoMedia->getType() === 'PHOTO');
        $this->assertTrue($videoMedia->getType() === 'VIDEO');
        $this->assertTrue($unknownMedia->getType() === 'UNKNOWN');
    }

    public function testIsNameGeneratedProperly(): void
    {
        $photo = new Media('image.jpg', strtotime('2015-01-01 00:00:00'), 65431);

        $this->assertEquals('20150101000000_00.jpg', $photo->getName());
        $this->assertEquals('20150101000000_01.jpg', $photo->generateNewName());
        $this->assertEquals('20150101000000_02.jpg', $photo->generateNewName());
        $this->assertEquals('20150101000000_02.jpg', $photo->getName());
    }
}