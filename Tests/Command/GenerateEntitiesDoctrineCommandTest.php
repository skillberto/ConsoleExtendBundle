<?php
/**
 * Created by PhpStorm.
 * User: heiszler_n
 * Date: 2016.01.08.
 * Time: 16:43
 */

namespace Skillberto\ConsoleExtendBundle\Tests\Command;

use Skillberto\ConsoleExtendBundle\Command\GenerateEntitiesDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class GenerateEntitiesDoctrineCommandTest extends KernelTestCase
{
    protected
        $baseCommand,
        $fs;

    protected function setUp()
    {
        $this->baseCommand = array(
            'command' => 'doctrine:generate:entities',
            'name'    => 'SkillbertoConsoleExtendBundle',
            '--path'  => __DIR__.'/../app/Resources/config/doctrine/*'
        );

        $this->fs = new Filesystem();
        $this->fs->mirror(__DIR__."/../app/Resources", __DIR__."/../../Resources");*/

        self::bootKernel();
    }

    protected function tearDown()
    {
        $this->fs->remove(__DIR__.'/../app/cache');
        $this->fs->remove(__DIR__.'/../app/logs');
    }


    public function testGenerate()
    {
        $tester = $this->createTester();
        $tester->execute($this->baseCommand, array("verbosity" => true, "decorated" => true, "interactive" => true));

        $this->assertEquals(0, $tester->getStatusCode());
    }

    public function testGenerateWithExtend()
    {
        /*$tester = $this->createTester();
        $tester->run(array_merge(array('extend' => 'TestExtended', $this->baseCommand)));

        $this->assertEquals(0, $tester->getStatusCode());*/
    }

    protected function createTester()
    {
        $application = new Application(self::$kernel);

        $application->setAutoExit(false);

        $command = new GenerateEntitiesDoctrineCommand();

        $command->setContainer($this->getContainer());
        $application->add($command);

        return new CommandTester($command);
    }

    protected function getContainer()
    {
        return self::$kernel->getContainer();
    }
}