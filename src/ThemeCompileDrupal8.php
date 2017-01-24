<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8;

use DigipolisGent\Robo\Task\Package\ThemeCompile;
use Robo\Task\Base\ParallelExec;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class ThemesCompileDrupal8 extends ParallelExec
{

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
        $dirs = empty($this->dirs)
            ? array_filter([$this->getConfig()->get('digipolis.root.project', false), $this->getConfig()->get('digipolis.root.web')])
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
        foreach ($finder as $infoFile) {
            $path = dirname($infoFile->getRealPath());
            $theme = $infoFile->getBasename('.info.yml');
            $command = $themes[$theme];
            $this->processes[] = new Process(
                $this->receiveCommand(new ThemeCompile($path, $command)),
                $path,
                null,
                null,
                null
            );
        }
        return parent::run();
    }
}
