<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8;

use DigipolisGent\Robo\Task\Package\PackageProject;
use Symfony\Component\Finder\Finder;

class PackageDrupal8 extends PackageProject
{

    /**
     * {@inheritdoc}
     */
    protected $ignoreFileNames = [
        'LICENSE',
        'LICENSE.txt',
        'README',
        'README.txt',
        'README.md',
    ];

    /**
     * {@inheritdoc}
     */
    protected function getFiles()
    {
        $dir = $this->dir;
        if (is_null($dir)) {
            $projectRoot = $this->getConfig()->get('digipolis.root.project', null);
            $dir = is_null($projectRoot)
                ? getcwd()
                : $projectRoot;
        }
        $finder = new Finder();
        $finder->in([
            $dir . '/vendor',
            $dir . '/web',
            $dir . '/config',
        ]);
        $finder->ignoreDotFiles(false);
        // Ignore dotfiles except .htaccess.
        $finder->notPath('/(^|\/)\.(?!(htaccess$)).+(\/|$)/');

        // Ignore other files defined by the dev.
        foreach ($this->ignoreFileNames as $fileName) {
            $finder->notName($fileName);
        }
        $dirs = [];
        foreach ($finder as $file) {
          $relative = substr($file->getRealPath(), strlen($dir) + 1);
          $dirs[$relative] = $file->getRealPath();
        }
        return $dirs;
    }
}
