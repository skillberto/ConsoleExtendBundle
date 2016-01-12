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

class GenerateEntitiesDoctrineCommandTest extends KernelTestCase
{
    protected $baseCommand = array('command' => 'doctrine:generate:entities', 'name' => 'SkillbertoConsoleExtendBundle');

    protected function setUp()
    {
        self::bootKernel();
    }

    public function testGenerate()
    {
        $tester = $this->getTester();
        $tester->run($this->baseCommand);

        $this->assertEquals(0, $tester->getStatusCode());
    }

    public function testGenerateWithExtend()
    {
        $tester = $this->getTester();
        $tester->run(array_merge(array('extend' => 'TestExtended', $this->baseCommand)));

        $this->assertEquals(0, $tester->getStatusCode());
    }

    protected function getTester()
    {
        $command = new GenerateEntitiesDoctrineCommand();
        $application = new Application(self::$kernel);
        $application->add($command);

        return new ApplicationTester($application);
    }
}