<?php

namespace DigipolisGent\Tests\Robo\Task\Package\Drupal8;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Robo\Common\CommandArguments;
use Robo\Contract\ConfigAwareInterface;
use Robo\Robo;
use Robo\TaskAccessor;
use Symfony\Component\Console\Output\NullOutput;

class PackageDrupal8Test extends \PHPUnit_Framework_TestCase implements ContainerAwareInterface, ConfigAwareInterface
{

    use \DigipolisGent\Robo\Task\Package\Drupal8\loadTasks;
    use TaskAccessor;
    use ContainerAwareTrait;
    use CommandArguments;
    use \Robo\Task\Base\loadTasks;
    use \Robo\Common\ConfigAwareTrait;

    protected $tarname = 'project.tar.gz';

    /**
     * Set up the Robo container so that we can create tasks in our tests.
     */
    public function setUp()
    {
        $container = Robo::createDefaultContainer(null, new NullOutput());
        $this->setContainer($container);
        $this->setConfig(Robo::config());
    }

    public function tearDown()
    {
        unlink($this->tarname);
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
        $projectPath = realpath(__DIR__ . '/../testfiles');
        $this->getConfig()->set('digipolis.root.project', $projectPath);
        $result = $this->taskPackageDrupal8($this->tarname)->run();

        // Assert response.
        $this->assertEquals('', $result->getMessage());
        $this->assertEquals(0, $result->getExitCode());

        // Assert the tar was created.
        $this->assertFileExists($this->tarname);

        // Assert the tar contents.
        $tar = new \Archive_Tar($this->tarname);
        $archiveFiles = [];
        foreach ($tar->listContent() as $archiveFile) {
            $archiveFiles[$archiveFile['filename']] = $archiveFile;
        }
        $expected = [
            'config/config.yml',
            'vendor/vendorlib/libfile.php',
            'web/.htaccess',
            'web/index.php',
        ];
        foreach ($expected as $file) {
          $this->assertArrayHasKey($file, $archiveFiles);
          unset($archiveFiles[$file]);
        }
        $this->assertEmpty($archiveFiles);
    }
}
