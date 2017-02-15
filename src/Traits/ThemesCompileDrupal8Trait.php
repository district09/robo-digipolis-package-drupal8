<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8\Traits;

use DigipolisGent\Robo\Task\Package\Drupal8\ThemesCompileDrupal8;

trait ThemesCompileDrupal8Trait
{
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
}
