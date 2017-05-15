<?php
/**
 * Created by PhpStorm.
 * User: 236
 * Date: 2017-05-15
 * Time: 07:38
 */

namespace MediaSeekerTest\FileSystem;


use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use MediaSeeker\FileSystem\FileSystem;

class FileSystemTest extends TestCase
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

    public function testSearchingFilesReturnsProperResults()
    {
        $fileSystem = new FileSystem();

        $files = $fileSystem->findFilesInPath($this->root->url(), ['jpeg']);
        $this->assertTrue(count($files) === 2);

        $files = $fileSystem->findFilesInPath($this->root->url(), ['jpg']);
        $this->assertTrue(count($files) === 5);

        $files = $fileSystem->findFilesInPath($this->root->url(), ['png']);
        $this->assertTrue(count($files) === 4);

        $files = $fileSystem->findFilesInPath($this->root->url(), ['png', 'jpeg']);
        $this->assertTrue(count($files) === 6);
    }
}