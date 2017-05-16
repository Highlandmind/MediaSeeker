<?php
/**
 * Created by PhpStorm.
 * User: 236
 * Date: 2017-05-16
 * Time: 08:53
 */

namespace MediaSeekerTest;


use MediaSeeker\FileSystem\FileSystemInterface;
use MediaSeeker\ImageStore;
use MediaSeeker\Models\Media;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

class ImageStoreTest extends TestCase
{
    /** @var  vfsStreamDirectory */
    private $root;

    public function setUp()
    {
        $structure = [
            'photos' => [
                'stuff' => [
                    'photo1.jpg' => 'photo1',
                    'photo2.jpeg' => 'photo2',
                    'video1.mts' => 'some vid'
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

    public function testPhotoFileIsStoredProperly(): void
    {
        $store = new ImageStore($this->getFilesystemClass(), '');

        $this->assertFileNotExists("vfs://images/2015/01 January/20150101000000_00.jpg");
        $this->assertFileExists("vfs://images/photos/stuff/photo1.jpg");

        $media = new Media("vfs://images/photos/stuff/photo1.jpg", strtotime('2015-01-01 00:00:00'), 0);
        $store->store($media);
        $this->assertFileExists("vfs://images/2015/01 January/20150101000000_00.jpg");
    }

    private function getFilesystemClass(): FileSystemInterface
    {
        return new class($this->root) implements FileSystemInterface {
            protected $root;
            public function __construct(vfsStreamDirectory $root)
            {
                $this->root = $root;
            }

            public function copy(string $source, string $destination)
            {
                $dirs = explode(DIRECTORY_SEPARATOR, pathinfo($destination, PATHINFO_DIRNAME));

                /** @var vfsStreamDirectory|null $last */
                $last = null;

                foreach ($dirs as $dirName) {
                    if (!empty($dirName)) {
                        $dir = new vfsStreamDirectory($dirName);

                        if ($last === null) {
                            $this->root->addChild($dir);
                        } else {
                            $last->addChild($dir);
                        }

                        $last = $dir;
                    }
                }

                vfsStream::newFile(pathinfo($destination, PATHINFO_BASENAME))
                    ->withContent('test')
                    ->at($last);

                return true;
            }

            public function findFilesInPath($path, array $extensions)
            {
                return [];
            }
        };
    }
}