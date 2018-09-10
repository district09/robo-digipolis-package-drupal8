# Robo Digipolis Package Drupal8

Drupal 8 Packaging/Compile tasks for Robo Task Runner

[![Latest Stable Version](https://poser.pugx.org/digipolisgent/robo-digipolis-package-drupal8/v/stable)](https://packagist.org/packages/digipolisgent/robo-digipolis-package-drupal8)
[![Latest Unstable Version](https://poser.pugx.org/digipolisgent/robo-digipolis-package-drupal8/v/unstable)](https://packagist.org/packages/digipolisgent/robo-digipolis-package-drupal8)
[![Total Downloads](https://poser.pugx.org/digipolisgent/robo-digipolis-package-drupal8/downloads)](https://packagist.org/packages/digipolisgent/robo-digipolis-package-drupal8)
[![License](https://poser.pugx.org/digipolisgent/robo-digipolis-package-drupal8/license)](https://packagist.org/packages/digipolisgent/robo-digipolis-package-drupal8)

[![Build Status](https://travis-ci.org/digipolisgent/robo-digipolis-package-drupal8.svg?branch=develop)](https://travis-ci.org/digipolisgent/robo-digipolis-package-drupal8)
[![Maintainability](https://api.codeclimate.com/v1/badges/8f19beedf27fd62dcdf5/maintainability)](https://codeclimate.com/github/digipolisgent/robo-digipolis-package-drupal8/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/8f19beedf27fd62dcdf5/test_coverage)](https://codeclimate.com/github/digipolisgent/robo-digipolis-package-drupal8/test_coverage)
[![PHP 7 ready](https://php7ready.timesplinter.ch/digipolisgent/robo-digipolis-package-drupal8/develop/badge.svg)](https://travis-ci.org/digipolisgent/robo-digipolis-package-drupal8)

## Commands

This package provides default commands wich you can use in your `RoboFile.php`
like so:

```php
class RoboFile extends \Robo\Tasks
{
    use \DigipolisGent\Robo\Task\Package\Drupal8\Commands\loadCommands;
}
```

### digipolis:package-project

`vendor/bin/robo digipolis:package-drupal8 FILE [DIR] [OPTIONS]`

#### Arguments

##### FILE

The name of the archive file that will be created.

##### DIR

The directory to package. Defaults to the config value `digipolis.root.project`
if it is set (see <https://github.com/digipolisgent/robo-digipolis-general> for
more information), or the current working directory otherwise.

#### Options

##### --ignore, -i

Comma separated list of filenames to ignore, has sensible defaults for Drupal 8
projects

### digipolis:themes-clean-drupal8

`vendor/bin/robo digipolis:themes-clean-drupal8 [THEMES] [DIRS]`

#### Arguments

##### THEMES

Comma-seperated list of Drupal theme machine names. Defaults to the keys of the
digipolis.themes.drupal8 config value.

##### DIRS

Comma-seperated list of directories in which to search for the themes. Defaults
to the digipolis.root.project and digipolis.root.web config values, or the
current working directory if that is not set.

### digipolis:themes-compile-drupal8

`vendor/bin/robo digipolis:themes-compile-drupal8 [THEMES] [DIRS]`

#### Arguments

##### THEMES

Comma-seperated list of Drupal theme machine names, or comma separated list in
the format `themename:command` where `themename` is the name of the theme to
compile and `command` is the name of the grunt/gulp command to execute (defaults
to `compile`). Defaults to the digipolis.themes.drupal8 config value.

##### DIRS

Comma-seperated list of directories in which to search for the themes. Defaults
to the digipolis.root.project and digipolis.root.web config values, or the
current working directory if that is not set.
