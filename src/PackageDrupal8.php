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
    protected function prepareMirrorDir()
    {
        $this->printTaskInfo(sprintf('Preparing directory %s.', $this->tmpDir));
        // Only keep web, vendor and config folder.
        $folders = new Finder();
        $folders->in($this->tmpDir);
        $folders->depth(0);
        $folders->notPath('/^(web|vendor|config|RoboFile\.php|properties\.yml|load\.environment\.php|composer\.json)$/');
        $folders->ignoreDotFiles(false);
        $this->fs->remove($folders);

        if (empty($this->ignoreFileNames)) {
            return;
        }
        $files = new Finder();
        $files->in($this->tmpDir);
        $files->ignoreDotFiles(false);

        $dotfiles = clone $files;

        $files->files();

        // Ignore files defined by the dev.
        foreach ($this->ignoreFileNames as $fileName) {
            $files->name($fileName);
        }
        $this->fs->remove($files);

        // Remove dotfiles except .htaccess.
        $dotfiles->path('/(^|\/)\.(?!(htaccess$)).+(\/|$)/');
        $this->fs->remove($dotfiles);
    }
}
