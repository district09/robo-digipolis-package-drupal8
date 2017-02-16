<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8;

use Robo\Contract\BuilderAwareInterface;
use Robo\Task\BaseTask;
use Symfony\Component\Finder\Finder;

class ThemesCleanDrupal8 extends BaseTask implements BuilderAwareInterface
{
    use \Robo\TaskAccessor;
    use \DigipolisGent\Robo\Task\Package\loadTasks;
    use Utility\ThemeFinder;

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
     *   An array of Drupal theme machine names. Defaults to the keys of the
     *   digipolis.themes.drupal8 config value.
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
     * Sets the themes to clean.
     *
     * @param array $themes
     *   An array of Drupal theme machine names. Defaults to the keys of the
     *   digipolis.themes.drupal8 config value.
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
        $themesFromConfig = $this->getConfig()->get('digipolis.themes.drupal8', false);
        if ($themesFromConfig) {
            $themesFromConfig = array_keys((array) $themesFromConfig);
        }
        $themes = empty($this->themes)
            ? $themesFromConfig
            : $this->themes;
        if (!$themes) {
            return \Robo\Result::success($this);
        }
        $collection = $this->collectionBuilder();
        foreach ($this->getThemePaths($themes) as $path) {
            $collection->addTask($this->taskThemeClean($path));
        }
        return $collection->run();
    }
}
