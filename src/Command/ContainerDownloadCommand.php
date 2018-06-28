<?php

// src/Command/ContainerDownloadCommand.php
namespace App\Command;

use Monolog\Logger;
use App\RackFiles\RackAuth;
use App\RackFiles\DownloadOperations;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ContainerDownloadCommand extends Command
{
    /**
     * Application logger interface
     *
     * @var Logger
     */
    private $logger;

    /**
     * Command constructor
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
        ->setName('container:download')
        ->setDescription('Downlaod a container.')
        ->setHelp('This command allows you to download a container...')
        ->addArgument('user', InputOption::VALUE_REQUIRED, 'User Name')
        ->addArgument('key', InputOption::VALUE_REQUIRED, 'Access Key')
        ->addArgument('region', InputOption::VALUE_REQUIRED, 'Rackspace Region')
        ->addArgument('container', InputOption::VALUE_REQUIRED, 'Container Name')
        ->addOption('savepath', 'd', InputOption::VALUE_OPTIONAL, 'A directory path to store files');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rackAuth = new RackAuth(
            $input->getArgument('user'),
            $input->getArgument('key'),
            $input->getArgument('region')
        );

        $downloadService = new DownloadOperations($rackAuth, $output);

        $container = $input->getArgument('container');
        $savePath = $input->getOption('savepath');

        if ($savePath == null || $savePath == false) {
            $savePath = '~/Desktop';
        }

        $this->logger->info('Startted : '. $savePath);
        $downloadService->download($container, $savePath);
    }
}
