<?php

namespace Skillberto\ConsoleExtendBundle\Command;

use Doctrine\ORM\Tools\EntityGenerator;
use Doctrine\Bundle\DoctrineBundle\Command\GenerateEntitiesDoctrineCommand as Base;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class GenerateEntitiesDoctrineCommand
 * @package Skillberto\ConsoleExtendBundle\Command
 * @author Norbert Heiszler <skillbertoo@gmail.com> 
 */
class GenerateEntitiesDoctrineCommand extends Base
{
    protected
        $extended;

    protected function configure()
    {
        parent::configure();
        $this->addOption('extend', null, InputOption::VALUE_REQUIRED,
            'Defines a base class to be extended by generated entity classes.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (($extend = $input->getOption('extend')) !== null) {
            $this->extended = $extend;
        }

        parent::execute($input, $output);
    }

    protected function getEntityGenerator()
    {
        $entityGenerator = parent::getEntityGenerator();
        $entityGenerator->setFieldVisibility(EntityGenerator::FIELD_VISIBLE_PROTECTED);
        $entityGenerator->setClassToExtend($this->extended);

        return $entityGenerator;
    }
} 