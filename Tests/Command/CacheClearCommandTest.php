<?php
/**
 * Created by PhpStorm.
 * User: heiszler_n
 * Date: 2016.01.08.
 * Time: 16:31
 */

namespace Skillberto\ConsoleExtendBundle\Tests\Command;

use Skillberto\ConsoleExtendBundle\Tests\AppKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Config\Resource\ResourceInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class CacheClearCommandTest extends WebTestCase
{
    protected function setUp()
    {
        $fs = new Filesystem();
        $fs->remove(__DIR__.'/../app/cache/*');
        $fs->remove(__DIR__.'/../app/logs/*');

        // BC, SF >=2.5
        if (method_exists($this, 'bootKernel')) {
            self::bootKernel();
        } else {
            if (null !== static::$kernel) {
                static::$kernel->shutdown();
            }
            static::$kernel = static::createKernel();
            static::$kernel->boot();
        }
    }

    public function testCacheIsFreshAfterCacheClearedWithWarmup()
    {
       $arguments = array(
            'command' => 'cache:clear',
            '-m'      => 1024,
        );

        $this->coreTest($arguments);

        $arguments = array(
            'command'        => 'cache:clear',
            '--memory_limit' => 1024,
        );

        $this->coreTest($arguments);
    }

    protected function coreTest(array $arguments)
    {
        $input = new ArrayInput($arguments);
        $application = new Application(static::$kernel);
        $application->setCatchExceptions(false);

        $application->doRun($input, new NullOutput());

        // Ensure that all *.meta files are fresh
        $finder = new Finder();
        $metaFiles = $finder->files()->in(static::$kernel->getCacheDir())->name('*.php.meta');
        // simply check that cache is warmed up
        $this->assertGreaterThanOrEqual(1, count($metaFiles));

        foreach ($metaFiles as $file) {
            $configCache = new ConfigCache(substr($file, 0, -5), true);
            $this->assertTrue(
                $configCache->isFresh(),
                sprintf(
                    'Meta file "%s" is not fresh',
                    (string) $file
                )
            );
        }

        // check that app kernel file present in meta file of container's cache
        $containerRef = new \ReflectionObject(static::$kernel->getContainer());
        $containerFile = $containerRef->getFileName();
        $containerMetaFile = $containerFile.'.meta';
        $kernelRef = new \ReflectionObject(static::$kernel);
        $kernelFile = $kernelRef->getFileName();
        /** @var ResourceInterface[] $meta */
        $meta = unserialize(file_get_contents($containerMetaFile));
        $found = false;
        foreach ($meta as $resource) {
            if ((string) $resource === $kernelFile) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Kernel file should present as resource');
        $this->assertRegExp(sprintf('/\'kernel.name\'\s*=>\s*\'%s\'/', static::$kernel->getName()), file_get_contents($containerFile), 'kernel.name is properly set on the dumped container');
        $this->assertEquals(ini_get('memory_limit'), '1024M');
    }

}