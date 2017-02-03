<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8\Traits;

use DigipolisGent\Robo\Task\Package\Drupal8\ThemesCleanDrupal8;

trait ThemesCleanDrupal8Trait
{
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
