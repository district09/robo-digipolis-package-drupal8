<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8;

trait loadTasks
{
    /**
     * Creates a PackageDrupal8 task.
     *
     * @param string $archiveFile
     *   The full path and name of the archive file to create.
     * @param string $dir
     *   The directory to package. Defaults to digipolis.root.project, or to the
     *   current working directory if that's not set.
     *
     * @return \DigipolisGent\Robo\Task\Package\Drupal8\PackageDrupal8
     *   The package project task.
     */
    protected function taskPackageDrupal8($archiveFile, $dir = null)
    {
        return $this->task(PackageDrupal8::class, $archiveFile, $dir);
    }

    /**
     * Creates a ThemesCompileDrupal8 task.
     *
     * @param array $themes
     *   An associative array where the keys are the Drupal theme machine names
     *   and the values are the respective Grunt/Gulp commands to execute.
     *   Defaults to the digipolis.themes.drupal8 config value.
     * @param array $dirs
     *   The directories in which to search for the themes. Defaults to the
     *   digipolis.root.project and digipolis.root.web config values, or the
     *   current working directory if that is not set.
     *
     * @return \DigipolisGent\Robo\Task\Package\Drupal8\ThemesCompileDrupal8
     *   The theme compile task.
     */
    protected function taskThemesCompileDrupal8($themes = [], $dirs = null)
    {
        return $this->task(ThemesCompileDrupal8::class, $themes, $dirs);
    }

    /**
     * Creates a ThemesCleanDrupal8 task.
     *
     * @param array $themes
     *   An array of Drupal theme machine names. Defaults to the keys of the
     *   digipolis.themes.drupal8 config value.
     * @param array $dirs
     *   The directories in which to search for the themes. Defaults to the
     *   digipolis.root.project and digipolis.root.web config values, or the
     *   current working directory if that is not set.
     *
     * @return \DigipolisGent\Robo\Task\Package\Drupal8\ThemesCleanDrupal8
     *   The theme clean task.
     */
    protected function taskThemesCleanDrupal8($themes = [], $dirs = null)
    {
        return $this->task(ThemesCleanDrupal8::class, $themes, $dirs);
    }
}
