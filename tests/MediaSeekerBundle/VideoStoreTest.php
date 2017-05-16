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
use MediaSeeker\VideoStore;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

class VideoStoreTest extends TestCase
{
    /** @var  vfsStreamDirectory */
    private $root;

    public function setUp()
    {
        $structure = [
            'photos' => [
                'stuff' => [
                    'video1.mts' => 'some vid'
                ]
            ],
        ];

        $this->root = vfsStream::setup('images', null, $structure);
    }

    public function testVideoFileIsStoredProperly(): void
    {
        $store = new VideoStore($this->getFilesystemClass(), '');

        $this->assertFileNotExists("vfs://images/2015/01 January/Filmy/20150101000000_00.mts");
        $this->assertFileExists("vfs://images/photos/stuff/video1.mts");

        $media = new Media("vfs://images/photos/stuff/video1.mts", strtotime('2015-01-01 00:00:00'), 0);
        $store->store($media);
        $this->assertFileExists("vfs://images/2015/01 January/Filmy/20150101000000_00.mts");
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