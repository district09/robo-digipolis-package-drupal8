<?php

namespace DigipolisGent\Tests\Robo\Task\Package;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Robo\Contract\ConfigAwareInterface;
use Robo\Common\CommandArguments;
use Robo\Robo;
use Robo\TaskAccessor;
use Symfony\Component\Console\Output\NullOutput;

class ThemesCleanDrupal8Test extends \PHPUnit_Framework_TestCase implements ContainerAwareInterface, ConfigAwareInterface
{

    use \DigipolisGent\Robo\Task\Package\Drupal8\loadTasks;
    use TaskAccessor;
    use ContainerAwareTrait;
    use CommandArguments;
    use \Robo\Task\Base\loadTasks;
    use \Robo\Common\ConfigAwareTrait;

    /**
     * Set up the Robo container so that we can create tasks in our tests.
     */
    public function setUp()
    {
        $container = Robo::createDefaultContainer(null, new NullOutput());
        $this->setContainer($container);
        $this->setConfig(Robo::config());
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
        $compileResult = $this->taskThemesCompileDrupal8()
            ->run();

        // Assert response.
        $this->assertEquals('', $compileResult->getMessage());
        $this->assertEquals(0, $compileResult->getExitCode());

        $result = $this->taskThemesCleanDrupal8()
            ->run();

        // Assert response.
        $this->assertEquals('', $result->getMessage());
        $this->assertEquals(0, $result->getExitCode());

        $themePaths = [
            realpath(__DIR__ . '/../testfiles/themes/testtheme'),
            realpath(__DIR__ . '/../testfiles/themes/custom/customtheme'),
        ];
        foreach ($themePaths as $themePath) {
          // Assert cleanup of npm files.
          $this->assertFileNotExists($themePath . '/node_modules');
        }

    }
}
