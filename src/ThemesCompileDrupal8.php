<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8;

use Robo\Contract\BuilderAwareInterface;
use Robo\Task\BaseTask;
use Symfony\Component\Finder\Finder;

class ThemesCompileDrupal8 extends BaseTask implements BuilderAwareInterface
{
    use \Robo\TaskAccessor;
    use \DigipolisGent\Robo\Task\Package\loadTasks;

    /**
     * An associative array where the keys are the Drupal theme machine names
     * and the values are the respective Grunt/Gulp commands to execute.
     *
     * @var array
     */
    protected $themes = [];

    /**
     * The directories in which to search for the themes.
     *
     * @var string
     */
    protected $dirs;

    /**
     * The Symfony finder to use to find the themes.
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;


    /**
     * Creates a new ThemesCompileDrupal8 task.
     *
     * @param array $themes
     *   An associative array where the keys are the Drupal theme machine names
     *   and the values are the respective Grunt/Gulp commands to execute.
     *   Defaults to the digipolis.themes.drupal8 config value.
     * @param array $dirs
     *   The directories in which to search for the themes. Defaults to the
     *   digipolis.root.project and digipolis.root.web config values, or the
     *   current working directory if that is not set.
     */
    public function __construct($themes = [], $dirs = null)
    {
        $this->themes = $themes;
        $this->dirs = $dirs;
        $this->finder = new Finder();

    }

    /**
     * Sets the themes to compile.
     *
     * @param array $themes
     *   An associative array where the keys are the Drupal theme machine names
     *   and the values are the respective Grunt/Gulp commands to execute.
     *   Defaults to the digipolis.themes.drupal8 config value.
     */
    public function themes($themes)
    {
        $this->themes = $themes;
    }

    /**
     * Sets the directories in which to search for themes.
     *
     * @param array $dirs
     *   The directories in which to search for the themes.
     */
    public function dirs($dirs)
    {
        $this->dirs = $dirs;
    }

    /**
     * Sets the finder.
     *
     * @param \Symfony\Component\Finder\Finder $finder
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function finder(Finder $finder)
    {
        $this->finder = $finder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $themes = empty($this->themes)
            ? $this->getConfig()->get('digipolis.themes.drupal8', false)
            : $this->themes;
        $dirsFromConfig = array_filter(
            [
                $this->getConfig()->get('digipolis.root.project', false),
                $this->getConfig()->get('digipolis.root.web', false),
            ]
        );
        $dirs = empty($this->dirs)
            ? $dirsFromConfig
            : $this->dirs;
        if (empty($dirs)) {
            $dirs = [getcwd()];
        }

        $finder = clone $this->finder;
        $finder->in($dirs)->files();
        foreach (array_keys($themes) as $themeName) {
            // Matches 'themes/(custom/){randomfoldername}/{themename}.info.yml'.
            $finder->path('/themes\/(custom\/)?[^\/]*\/' . preg_quote($themeName, '/') . '\.info\.yml/');
        }
        $processed = [];
        $collection = $this->collectionBuilder();
        foreach ($finder as $infoFile) {
            $path = dirname($infoFile->getRealPath());
            // The web dir can be a subdir of the project root (in most cases
            // really). Make sure we don't compile the same theme twice.
            if (isset($processed[$path])) {
              continue;
            }
            $processed[$path] = TRUE;
            $theme = $infoFile->getBasename('.info.yml');
            $command = $themes[$theme];
            $collection->addTask($this->taskThemeCompile($path, $command));
        }
        return $collection->run();
    }
}
