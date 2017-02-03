<?php

namespace DigipolisGent\Robo\Task\Package\Drupal8\Utility;

trait ThemeFinder
{

    /**
     * Get the theme paths.
     *
     * @param array $themes
     *   An array of theme names.
     *
     * @return array
     *   The theme paths keyed by theme name.
     */
    protected function getThemePaths($themes)
    {
        $dirsFromConfig = array_filter(
            [
                $this->getConfig()->get('digipolis.root.project', false),
                $this->getConfig()->get('digipolis.root.web', false),
            ]
        );
        $dirs = empty($this->dirs)
            ? $dirsFromConfig
            : $this->dirs;
        if (empty($dirs)) {
            $dirs = [getcwd()];
        }

        $finder = clone $this->finder;
        $finder->in($dirs)->files();
        foreach ($themes as $themeName) {
            // Matches 'themes/(custom/){randomfoldername}/{themename}.info.yml'.
            $finder->path('/themes\/(custom\/)?[^\/]*\/' . preg_quote($themeName, '/') . '\.info\.yml/');
        }
        $processed = [];
        $paths = [];
        foreach ($finder as $infoFile) {
            $path = dirname($infoFile->getRealPath());
            // The web dir can be a subdir of the project root (in most cases
            // really). Make sure we don't compile the same theme twice.
            if (isset($processed[$path])) {
                continue;
            }
            $processed[$path] = true;
            $theme = $infoFile->getBasename('.info.yml');
            $paths[$theme] = $path;
        }
        return $paths;
    }
}
