<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8\Robo\Plugin\Commands;

use DigipolisGent\Robo\Task\General\Common\DigipolisPropertiesAwareInterface;
use Robo\Symfony\ConsoleIO;

class DigipolisPackageDrupal8Commands extends \Robo\Tasks implements DigipolisPropertiesAwareInterface, \Robo\Contract\ConfigAwareInterface
{
    use \DigipolisGent\Robo\Task\Package\Drupal8\Tasks;
    use \DigipolisGent\Robo\Task\General\Common\DigipolisPropertiesAware;
    use \Consolidation\Config\ConfigAwareTrait;

    /**
     * @command digipolis:theme-compile-drupal8
     */
    public function digipolisThemesCompileDrupal8(ConsoleIO $io, $themes = '', $dirs = '')
    {
        $this->readProperties();
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

    /**
     * @command digipolis:theme-clean-drupal8
     */
    public function digipolisThemesCleanDrupal8(ConsoleIO $io, $themes = '', $dirs = '')
    {
        $this->readProperties();
        $this->taskThemesCleanDrupal8(
            explode(',', $themes),
            explode(',', $dirs)
        )->run();
    }

    /**
     * @command digipolis:package-project-drupal8
     */
    public function digipolisPackageDrupal8(ConsoleIO $io, $archiveFile, $dir = null, $opts = ['ignore|i' => ''])
    {
        $this->readProperties();
        $this->taskPackageDrupal8($archiveFile, $dir)
            ->ignoreFileNames(array_map('trim', explode(',', $opts['ignore'])))
            ->run();
    }
}
