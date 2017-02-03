<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8\Commands;

trait ThemesCompileDrupal8
{
    use \DigipolisGent\Robo\Task\Package\Drupal8\Traits\ThemesCompileDrupal8Trait;

    public function digipolisThemesCompileDrupal8($themes = '', $dirs = '')
    {
        if (is_callable([$this, 'readProperties'])) {
            $this->readProperties();
        }
        $themesWithCommand = [];
        foreach (explode(',', $themes) as $theme) {
            $parts = explode(':', $theme);
            $themesWithCommand[$parts[0]] = isset($parts[1])
                ? $parts[1]
                : 'compile';
        }
        $this->taskThemesCompileDrupal8(
            $themesWithCommand,
            explode(',', $dirs)
        )->run();
    }
}
