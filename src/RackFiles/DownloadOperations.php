<?php

namespace App\RackFiles;

use App\RackFiles\RackAuth;
use App\FileSystem\FileSystem;
use App\Exceptions\ContainerNotFoundException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadOperations
{
    /**
     * Storage Client
     *
     * @var object
     */
    private $storageClient;

    private $output;

    public function __construct(RackAuth $auth, OutputInterface $output)
    {
        $this->storageClient = $auth->objectStorage;
        $this->output = $output;
    }

    public function download($containerName, $savePath)
    {
        $conlist = $this->storageClient->listContainers();

        $foundContainer = false;

        while ($container = $conlist->Next()) {
            if ($container->name == $containerName) {
                $foundContainer = true;

                $files = $container->ObjectList([ 'limit' => 10000 ]);
                $total = $files->count();

                $progressBar = new ProgressBar($this->output, $total);
                $progressBar->start();

                while ($o = $files->Next()) {
                    $fileName = $o->getName();

                    try {
                        $file = $container->getObject($fileName);
                        $stream = $file->getContent();
                        
                        // Cast to string
                        $content = (string) $stream;

                        $stream->rewind();
                        $fileContent = $stream->getStream();
                    } catch (\Exception $e) {
                        file_put_contents($savePath . '/failed.txt', $fileName . PHP_EOL, FILE_APPEND | LOCK_EX);
                        continue;
                    }
                    
                    $writePath = implode(DIRECTORY_SEPARATOR, [$savePath, $containerName, $fileName]);
                    FileSystem::write($writePath, $fileContent);
                    
                    $progressBar->advance();
                }

                $progressBar->finish();
            }
        }

        if (!$foundContainer) {
            throw new ContainerNotFoundException();
        }
    }
}
