<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8\Commands;

trait ThemesCleanDrupal8
{
    use \DigipolisGent\Robo\Task\Package\Drupal8\Traits\ThemesCleanDrupal8Trait;

    public function digipolisThemesCleanDrupal8($themes = '', $dirs = '')
    {
        if (is_callable([$this, 'readProperties'])) {
            $this->readProperties();
        }
        $this->taskThemesCleanDrupal8(
            explode(',', $themes),
            explode(',', $dirs)
        )->run();
    }
}
