<?php

namespace App\Bundle\CoreBundle\Command;

use Dizda\CloudBackupBundle\Event\RestoreEvent;
use Dizda\CloudBackupBundle\Manager\DatabaseManager;
use Dizda\CloudBackupBundle\Manager\ProcessorManager;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Dizda\CloudBackupBundle\Manager\RestoreManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class RestoreBackupCommand extends Command
{
    /**
     * @var RestoreManager
     */
    protected $restoreManager;

    /**
     * @var ProcessorManager
     */
    private $processorManager;

    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @var string
     */
    private $restoreFolder;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $webDir;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param RestoreManager $restoreManager
     * @param ProcessorManager $processorManager
     * @param DatabaseManager $databaseManager
     * @param string $restoreFolder
     * @param Filesystem $filesystem
     * @param string $webDir
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        RestoreManager $restoreManager,
        ProcessorManager $processorManager,
        DatabaseManager $databaseManager,
        $restoreFolder,
        Filesystem $filesystem,
        $webDir,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->restoreManager = $restoreManager;
        $this->processorManager = $processorManager;
        $this->databaseManager = $databaseManager;
        $this->restoreFolder = $restoreFolder;
        $this->filesystem = $filesystem;
        $this->webDir = $webDir;
        $this->eventDispatcher = $eventDispatcher;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:backup:restore')
            ->setDescription('Restore backup')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Backup path'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // /home/anton/projects/shop/var/db_dump/hostname_2017-10-19_23-23-35.zip
        $filePath = $input->getArgument('path');

        if (file_exists($filePath)) {
            $this->filesystem->mkdir($this->restoreFolder);
            $file = $this->download($filePath);
            $this->processorManager->uncompress($file);
            $this->restoreFiles($this->restoreFolder);
            $this->databaseManager->restore();
            $this->eventDispatcher->dispatch(RestoreEvent::RESTORE_COMPLETED, new RestoreEvent($file));
        }
    }

    /**
     * @param $filePath
     * @return SplFileInfo
     */
    private function download($filePath)
    {
        $content = file_get_contents($filePath);
        $fileName = explode('/', $filePath);
        $fileName = end($fileName);
        $splFile = new \SplFileInfo($this->restoreFolder . $fileName);
        $this->filesystem->dumpFile($splFile->getPathname(), $content);

        return $splFile;
    }

    /**
     * @param string $restoreDir
     */
    private function restoreFiles($restoreDir)
    {
        $restoreDir = $restoreDir . 'folders/web';
        $webDir = $this->webDir . '/../web';
        $finder = new Finder();
        $finder->directories()->in($restoreDir)->depth('== 0');

        foreach ($finder as $dir) {
            $dirPath = $dir->getRealPath();
            $dirName = $dir->getRelativePathname();

            $directoryIterator = new \RecursiveDirectoryIterator($dirPath, \FilesystemIterator::SKIP_DOTS);
            $iterator = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::SELF_FIRST);

            $this->filesystem->mkdir($webDir . DIRECTORY_SEPARATOR . $dirName);

            foreach ($iterator as $key => $item) {
                if ($item->isDir()) {
                    $targetDir = $webDir . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                    $this->filesystem->mkdir($targetDir);
                } else {
                    $targetFilename = $webDir . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                    $this->filesystem->copy($item, $targetFilename, true);
                }
            }
        }
    }
}
