<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8\Commands;

trait PackageDrupal8
{

    use \DigipolisGent\Robo\Task\Package\Drupal8\Traits\PackageDrupal8Trait;

    public function digipolisPackageDrupal8($archiveFile, $dir = null, $opts = ['ignore|i' => ''])
    {
        if (is_callable([$this, 'readProperties'])) {
            $this->readProperties();
        }
        $this->taskPackageDrupal8($archiveFile, $dir)
            ->ignoreFileNames(array_map('trim', explode(',', $opts['ignore'])))
            ->run();
    }
}
