<?php

namespace Skillberto\ConsoleExtendBundle\Command;

use TeamLab\Bundle\FixturesBundle\Command\DoctrineDumpCommand as BaseCommand;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DoctrineDumpCommand extends BaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();
        $entities = $em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();

        if ($input->isInteractive()) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('<question>Careful, existing data fixtures will be override. Do you want to continue Y/N ?</question>', false);

            if (!$helper->ask($input, $output, $question)) {
                return;
            }
        }

        foreach($entities as $entityName) {
            $provider = new EntityProvider($entityName);
            if(!$provider->valid()) continue;


            $fields = $provider->getDumpFields();

            if (count($fields) == 0) {
                $output->writeln(sprintf('<error>No fields for entity:</error> "<info>%s</info>".', $entityName));
                continue;
            }

            $output->writeln(sprintf('Generating dump file for entity "<info>%s</info>", fields: <info>%s</info>',
                $entityName, implode(', ', array_keys($fields))));

            try {
                $this->generateFixtures($provider, $entityName, $fields);

            } catch (CommandException $e) {
                $output->writeln($e->getMessage());
                exit(1);
            }
        }

        exit(0);
    }
}