<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8\Traits;

use DigipolisGent\Robo\Task\Package\Drupal8\PackageDrupal8;

trait PackageDrupal8Trait
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
}
