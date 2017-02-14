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
        'CHANGELOG.txt',
        'COPYRIGHT.txt',
        'INSTALL.mysql.txt',
        'INSTALL.pgsql.txt',
        'INSTALL.sqlite.txt',
        'INSTALL.txt',
        'MAINTAINERS.txt',
        'UPDATE.txt',
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
        $finder->ignoreDotFiles(false);
        // Ignore dotfiles except .htaccess.
        $finder->notPath('/(^|\/)\.(?!(htaccess$)).+(\/|$)/');

        // Ignore other files defined by the dev.
        foreach ($this->ignoreFileNames as $fileName) {
            $finder->notName($fileName);
        }
        $dirs = [];
        $finderClone = clone $finder;
        $finder->in([
            $dir . '/vendor',
            $dir . '/web',
            $dir . '/config',
        ]);
        foreach ($finder as $file) {
            $realPath = $file->getRealPath();
            if (is_dir($realPath)) {
                $subDirFinder = clone $finderClone;
                // This is a directory that contains files that will be added.
                // So don't add the directory or files will be added twice.
                if ($subDirFinder->in($realPath)->files()->count()) {
                    continue;
                }
            }

            $relative = substr($realPath, strlen($dir) + 1);
            $dirs[$relative] = $realPath;
        }
        return $dirs;
    }
}
