<?php

namespace DigipolisGent\Tests\Robo\Task\Package;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use PHPUnit\Framework\TestCase;
use Robo\Common\CommandArguments;
use Robo\Contract\ConfigAwareInterface;
use Robo\Robo;
use Robo\TaskAccessor;
use Symfony\Component\Console\Output\NullOutput;

class ThemesCompileDrupal8Test extends TestCase implements ContainerAwareInterface, ConfigAwareInterface
{

    use \DigipolisGent\Robo\Task\Package\Drupal8\Tasks;
    use TaskAccessor;
    use ContainerAwareTrait;
    use CommandArguments;
    use \Robo\Task\Base\Tasks;
    use \Robo\Common\ConfigAwareTrait;

    protected $themePaths;
    protected $sourceThemePath;

    /**
     * Set up the Robo container so that we can create tasks in our tests.
     */
    public function setUp(): void
    {
        $container = Robo::createDefaultContainer(null, new NullOutput());
        $this->setContainer($container);
        $this->setConfig(Robo::config());
        $this->themePaths = [
            realpath(__DIR__ . '/../testfiles/themes/testtheme'),
            realpath(__DIR__ . '/../testfiles/themes/custom/customtheme'),
        ];
        $this->sourceThemePath = realpath(__DIR__ . '/../testfiles/themes/testtheme_source');
    }

    public function tearDown(): void
    {
        foreach ($this->themePaths as $themePath) {
            // Manual cleanup.
            $files = [
                '/hello_grunt.txt',
                '/node_modules',
                '/vendor',
            ];
            foreach ($files as $remove) {
                exec('rm -rf ' . $themePath . $remove);
            }
        }
        exec('rm -rf ' . $this->sourceThemePath . '/hello_grunt.txt');
        exec('rm -rf ' . $this->sourceThemePath . '/source/node_modules');
        exec('rm -rf ' . $this->sourceThemePath . '/source/vendor');
    }

    /**
     * Scaffold the collection builder.
     *
     * @return \Robo\Collection\CollectionBuilder
     *   The collection builder.
     */
    public function collectionBuilder()
    {
        $emptyRobofile = new \Robo\Tasks();

        return \Robo\Collection\CollectionBuilder::create($this->getContainer(), $emptyRobofile);
    }

    public function testRun()
    {
        $this->getConfig()->set('digipolis.root.project', realpath(__DIR__ . '/../testfiles'));
        $this->getConfig()->set('digipolis.themes.drupal8', [
            'testtheme' => 'build',
            'testtheme_source' => 'build',
            'custom' => 'build',
        ]);
        $result = $this->taskThemesCompileDrupal8()
            ->run();

        // Assert response.
        $this->assertEquals('', $result->getMessage());
        $this->assertEquals(0, $result->getExitCode());

        foreach ($this->themePaths as $themePath) {
            // Assert node ran.
            $this->assertFileExists($themePath . '/node_modules');

            // Assert grunt build ran.
            $this->assertFileExists($themePath . '/hello_grunt.txt');
        }

        // Assert node ran.
        $this->assertFileExists($this->sourceThemePath . '/source/node_modules');

        // Assert grunt build ran.
        $this->assertFileExists($this->sourceThemePath . '/hello_grunt.txt');
    }
}
