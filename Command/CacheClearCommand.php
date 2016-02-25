<?php


namespace Skillberto\ConsoleExtendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class CacheClearCommand
 * @package Skillberto\ConsoleExtendBundle\Command
 * @author Norbert Heiszler <skillbertoo@gmail.com> 
 */
class CacheClearCommand extends BaseCommand
{
    public function configure()
    {
        parent::configure();

        $this->addOption("memory_limit", "m", InputOption::VALUE_REQUIRED, "Set memory_limit in Megabytes.");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $memory = '1024';

        if ($input->getOption("memory_limit")) {
            $memory = $input->getOption("memory_limit");
        }

        ini_set("memory_limit", $memory."M");

        parent::execute($input, $output);

        $container = $this->getContainer();

        $cacheDir = $container->getParameter('kernel.cache_dir');
        $chmod = $container->getParameter('skillberto_console_extend.chmod');

        /** @var Filesystem $fs */
        $fs = $container->get('filesystem');
        $fs->chmod($cacheDir, $chmod, 0000, true);
    }
} 