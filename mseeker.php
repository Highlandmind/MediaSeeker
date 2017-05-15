#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use MediaSeeker\Command\SeekCommand;

$app = new Application();

$app->add(new SeekCommand(__DIR__));

$app->run();