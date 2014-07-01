# Which
A PHP library for locating commands in a PATH.

[![Build Status](http://img.shields.io/travis/nubs/which.svg?style=flat)](https://travis-ci.org/nubs/which)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/nubs/which.svg?style=flat)](https://scrutinizer-ci.com/g/nubs/which/)
[![Code Coverage](http://img.shields.io/scrutinizer/coverage/g/nubs/which.svg?style=flat)](https://scrutinizer-ci.com/g/nubs/which/)

[![Latest Stable Version](http://img.shields.io/packagist/v/nubs/which.svg?style=flat)](https://packagist.org/packages/nubs/which)
[![Total Downloads](http://img.shields.io/packagist/dt/nubs/which.svg?style=flat)](https://packagist.org/packages/nubs/which)
[![License](http://img.shields.io/packagist/l/nubs/which.svg?style=flat)](https://packagist.org/packages/nubs/which)

[![Dependency Status](https://www.versioneye.com/user/projects/53a01f7b83add749a300001e/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53a01f7b83add749a300001e)

## Requirements
This library requires PHP 5.3, or newer.

## Installation
This package uses [composer](https://getcomposer.org) so you can just add
`nubs\which` as a dependency to your `composer.json` file.

## Usage

### Constructing a Locator
There are several ways to create a locator.  The preferred way is to use the
[brianium/habitat](https://github.com/brianium/habitat) constructor.  Habitat
makes accessing the environment variables easy, even in cases where the `$_ENV`
superglobal isn't populated.  You can use it like this:
```php
$habitat = new \Habitat\Habitat();
$environment = $habitat->getEnvironment();
$locator = \Nubs\Which\Locator::createFromEnvironment($environment);
```

If you'd prefer not to add another external dependency, you don't have to use
Habitat and can instead use PHP's built in `getenv`:
```php
$locator = \Nubs\Which\Locator::createFromEnvironment();
```

Or you can construct the locator using the `PATH` environment variable
directly:
```php
$path = getenv('PATH');
$locator = \Nubs\Which\Locator::createFromPathEnvironmentVariable($path);
```

Finally, if you want full control over the paths that are searched, you can use
the constructor with the array of paths to search:
```php
$paths = array('/opt/special/bin', '/usr/local/bin', '/usr/bin', '/bin');
$locator = new \Nubs\Which\Locator($paths);
```

### Locating commands
The locator can find commands based off of its configured paths and will return
`null` if the command could not be found:
```php
$locator = \Nubs\Which\Locator::createFromEnvironment();

echo $locator->locate('php');
// /usr/bin/php

var_dump($locator->locate('asdf'));
// NULL
```

It can also be given an absolute path, in which case the configured paths are
ignored and only the absolute path is checked:
```php
$locator = \Nubs\Which\Locator::createFromEnvironment();

echo $locator->locate('/opt/php/bin/php');
// /opt/php/bin/php
```

Except for absolute paths, all other directory traversal is ignored and will
return `null` as though no matching command was found:
```php
$locator = \Nubs\Which\Locator::createFromEnvironment();

var_dump($locator->locate('foo/php'));
// NULL

var_dump($locator->locate('../bin/php'));
// NULL
```

Finally, an additional `locateAll` method is included.  If a command exists at
multiple places on the `PATH`, this will return all of them.  It behaves all
the rules as the standard `locate` method.
```php
$locator = \Nubs\Which\Locator::createFromEnvironment();

var_dump($locator->locateAll('php'));
// array(2) {
//   [0] =>
//   string(12) "/usr/bin/php"
//   [1] =>
//   string(16) "/opt/php/bin/php"
// }

var_dump($locator->locate('asdf'));
// array(0) {
// }

var_dump($locator->locateAll('/opt/php/bin/php'));
// array(1) {
//   [0] =>
//   string(16) "/opt/php/bin/php"
// }

var_dump($locator->locate('../bin/php'));
// array(0) {
// }
```

### CLI Interface
Also included is a `which` executable that works much like the standard which
utility.  It takes an arbitrary number of parameters and outputs the path to
each of them.

```bash
$ ./bin/which more vim foo
/bin/more
/usr/bin/vim
foo not found
```

The command is configured as a binary with composer, so it is accessible in
`vendor/bin/which` when you install the package.  For a global install, this
means that you can find it in `$COMPOSER_HOME/vendor/bin/which`.  This command
does require the symfony/console package to be installed and accessible via the
autoloader.
