<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Console\Application;
use App\Command\ContainerDownloadCommand;

$application = new Application();

$home = getenv("HOME");
$logpath = $home . '/rackfiles/appevents.log';

$logger = new Logger('my_logger');
$logger->pushHandler(new StreamHandler($logpath, Logger::INFO));

// Register download command
$application->add(new ContainerDownloadCommand($logger));

$application->run();
