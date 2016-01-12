<?php

use Skillberto\ConsoleExtendBundle\SkillbertoConsoleExtendBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new SkillbertoConsoleExtendBundle(),
        );
    }

    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.yml');
    }
}
