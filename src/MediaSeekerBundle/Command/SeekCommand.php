<?php

namespace MediaSeeker\Command;

use MediaSeeker\MediaSeeker;
use MediaSeeker\Models\Media;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use MediaSeeker\FileSystem\FileSystem;


class SeekCommand extends Command
{
    private $baseDir;

    public function __construct(string $dir, $name = null)
    {
        parent::__construct($name);
        $this->baseDir = $dir;
    }

    protected function configure()
    {
        $this
            ->setName('find')
            ->setDescription('Find files')
            ->setHelp('You can specify extensions by which files will be filtered')
            ->addOption(
                'ext',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'The extension of seeking file'
            )
            ->addOption(
                'extensions',
                null,
                InputOption::VALUE_OPTIONAL,
                'The list of files extensions to filter separated by comma ","'
            )
            ->addArgument(
                'paths',
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'Paths to search'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Searching files...'
        ]);

        $paths = $input->getArgument('paths');
        if (empty($paths)) {
            $output->writeln([
                'No paths specified for search. Root path was set to working directory'
            ]);

            $paths = [$this->baseDir];
        }

        $fileSystem = new FileSystem();
        $seeker = new MediaSeeker($fileSystem);

        $files = $seeker->collectMedia($paths, $this->getExtensions($input));

        $filesNumber = count($files);

        $output->writeln([
            "{$filesNumber} files found"
        ]);

        $output->writeln([
            "Organizing files..."
        ]);

        $seeker->organize($files);

        $output->writeln(['Done']);
    }

    private function getExtensions(InputInterface $input): array
    {
        $extensions = [];
        $ext1 = explode(',', $input->getOption('extensions'));

        foreach ($ext1 as $ext) {
            $extensions[] = trim($ext);
        }

        $extensions = array_merge($extensions, $input->getOption('ext'));

        return array_unique($extensions);
    }
}