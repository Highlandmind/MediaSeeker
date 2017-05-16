<?php

namespace MediaSeekerTest;

use MediaSeeker\FileSystem\FileSystem;
use MediaSeeker\MediaSeeker;
use MediaSeeker\MediaStore;
use MediaSeeker\Models\Media;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

class MediaSeekerTest extends TestCase
{
    /** @var  vfsStreamDirectory */
    private $root;

    public function setUp()
    {
        $structure = [
            'photos' => [
                'stuff' => [
                    'photo1.jpg' => 'photo1',
                    'photo2.jpeg' => 'photo2'
                ],
                'photo3.png' => 'photo3',
                'photo4.png' => 'photo4',
                'photo5.jpg' => 'photo5',
                'photo6.jpg' => 'photo6',
                'photo7.jpg' => 'photo7',
            ],
            'other-images' => [
                'photo3.png' => 'photo3',
                'photo4.png' => 'photo4',
                'photo5.jpg' => 'photo5',
                'photo2.jpeg' => 'photo2'
            ]
        ];

        $this->root = vfsStream::setup('images', null, $structure);
    }

    public function testShouldCollectProperObjects()
    {
        $seeker = new MediaSeeker(new FileSystem(), new MediaStore());

        $mediaFiles = $seeker->collectMedia([$this->root->url()], ['jpg']);

        $this->assertNotEmpty($mediaFiles);
    }
}