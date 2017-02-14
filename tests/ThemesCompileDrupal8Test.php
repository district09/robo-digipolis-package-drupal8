<?php

namespace DigipolisGent\Tests\Robo\Task\Package;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Robo\Contract\ConfigAwareInterface;
use Robo\Common\CommandArguments;
use Robo\Robo;
use Robo\TaskAccessor;
use Symfony\Component\Console\Output\NullOutput;

class ThemesCompileDrupal8Test extends \PHPUnit_Framework_TestCase implements ContainerAwareInterface, ConfigAwareInterface
{

    use \DigipolisGent\Robo\Task\Package\Drupal8\loadTasks;
    use TaskAccessor;
    use ContainerAwareTrait;
    use CommandArguments;
    use \Robo\Task\Base\loadTasks;
    use \Robo\Common\ConfigAwareTrait;

    protected $themePaths;

    /**
     * Set up the Robo container so that we can create tasks in our tests.
     */
    public function setUp()
    {
        $container = Robo::createDefaultContainer(null, new NullOutput());
        $this->setContainer($container);
        $this->setConfig(Robo::config());
        $this->themePaths = [
            realpath(__DIR__ . '/../testfiles/themes/testtheme'),
            realpath(__DIR__ . '/../testfiles/themes/custom/customtheme'),
        ];
    }

    public function tearDown()
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

        return $this->getContainer()
            ->get('collectionBuilder', [$emptyRobofile]);
    }

    public function testRun()
    {
        $this->getConfig()->set('digipolis.root.project', realpath(__DIR__ . '/../testfiles'));
        $this->getConfig()->set('digipolis.themes.drupal8', [
            'testtheme' => 'build',
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
    }
}
