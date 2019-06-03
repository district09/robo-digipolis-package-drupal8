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
        'install.php',
        'update.php',
    ];

    /**
     * Paths to directories/files to keep.
     *
     * Defaults to sensible paths when using
     * https://github.com/drupal-composer/drupal-project (including, but not
     * limited to, 'config', 'web', 'vendor').
     *
     * @var array
     */
    protected $keepPaths = [
        'web',
        'vendor',
        'config',
        'RoboFile.php',
        'properties.yml',
        'load.environment.php',
        'composer.json',
    ];

    /**
     * Set paths to directories/files to keep.
     *
     * Defaults to sensible paths when using
     * https://github.com/drupal-composer/drupal-project (including, but not
     * limited to, 'config', 'web', 'vendor').
     *
     * @param array $paths
     *   Paths to keep.
     *
     * @return $this
     */
    public function keepPaths(array $paths)
    {
        $this->keepPaths = $paths;

        return $this;
    }

    /**
     * Adds a path to keep during packaging.
     *
     * @param string $path
     *   Path to keep.
     *
     * @return $this
     */
    public function keepPath($path)
    {
        $this->keepPaths[] = $path;

        return $this;
    }

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
        $folders->notPath('/^(' . implode('|', array_map('preg_quote', $this->keepPaths)) . ')$/');
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
