# Which
A PHP library for locating commands in a PATH.

[![Build Status](http://img.shields.io/travis/nubs/which.svg?style=flat)](https://travis-ci.org/nubs/which)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/nubs/which.svg?style=flat)](https://scrutinizer-ci.com/g/nubs/which/)
[![Code Coverage](http://img.shields.io/coveralls/nubs/which.svg?style=flat)](https://coveralls.io/r/nubs/which)

[![Latest Stable Version](http://img.shields.io/packagist/v/nubs/which.svg?style=flat)](https://packagist.org/packages/nubs/which)
[![Total Downloads](http://img.shields.io/packagist/dt/nubs/which.svg?style=flat)](https://packagist.org/packages/nubs/which)
[![License](http://img.shields.io/packagist/l/nubs/which.svg?style=flat)](https://packagist.org/packages/nubs/which)

[![Dependency Status](https://www.versioneye.com/user/projects/53a01f7b83add749a300001e/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53a01f7b83add749a300001e)

## Requirements
This library requires PHP 5.4, or newer.

## Installation
This package uses [composer](https://getcomposer.org) so you can just add
`nubs/which` as a dependency to your `composer.json` file or execute the
following command:

```bash
composer require nubs/which
```

## Example
Here is a quick example to demonstrate how this library is generally meant to
be used:
```php
$locatorFactory = new \Nubs\Which\LocatorFactory\PlatformLocatorFactory();
$locator = $locatorFactory->create();

echo $locator->locate('php');
// /usr/bin/php
```

## Usage

### Constructing a Locator
There are several ways to create a locator.  The preferred way is to use the
[brianium/habitat](https://github.com/brianium/habitat) constructor.  Habitat
makes accessing the environment variables easy, even in cases where the `$_ENV`
superglobal isn't populated.  You can use it like this:
```php
$habitat = new \Habitat\Habitat();
$environment = $habitat->getEnvironment();
$locatorFactory = new \Nubs\Which\LocatorFactory\PlatformLocatorFactory();
$locator = $locatorFactory->create($environment);
```

Habitat environment is not necessary.  Without passing an environment, PHP's
built-in `getenv` will be used:
```php
$locatorFactory = new \Nubs\Which\LocatorFactory\PlatformLocatorFactory();
$locator = $locatorFactory->create();
```

There are also two platform-specific factories in case you don't want to rely
on the platform detection in the `PlatformLocatorFactory`.  If you are on a
POSIXy system (e.g., Linux, OSX, BSD), you can use the `PosixLocatorFactory`
and if you are on a Windows system you can use the `WindowsLocatorFactory`.
```php
$locatorFactory = new \Nubs\Which\LocatorFactory\PosixLocatorFactory();
$locator = $locatorFactory->create();

// or

$locatorFactory = new \Nubs\Which\LocatorFactory\WindowsLocatorFactory();
$locator = $locatorFactory->create();
```

Finally, if you want full control over the paths that are searched, you can use
specify exactly which paths to use:
```php
$paths = ['/opt/special/bin', '/usr/local/bin', '/usr/bin', '/bin'];
$pathBuilder = new \Nubs\Which\PathBuilder\PosixPathBuilder($paths);
$locator = new \Nubs\Which\Locator($pathBuilder);

// or

$paths = ['C:\\Windows\\System32', 'C:\\Windows'];
$pathExtensions = ['.exe', '.com'];
$pathBuilder = new \Nubs\Which\PathBuilder\WindowsPathBuilder(
    $paths,
    $pathExtensions
);
$locator = new \Nubs\Which\Locator($pathBuilder);
```

### Locating commands
The locator can find commands based off of its configured paths and will return
`null` if the command could not be found:
```php
$locatorFactory = new \Nubs\Which\LocatorFactory\PlatformLocatorFactory();
$locator = $locatorFactory->create();

echo $locator->locate('php');
// /usr/bin/php

var_dump($locator->locate('asdf'));
// NULL
```

It can also be given an absolute or a relative path, in which case the
configured paths are ignored and pathing is done based off the current
directory:
```php
$locatorFactory = new \Nubs\Which\LocatorFactory\PlatformLocatorFactory();
$locator = $locatorFactory->create();

echo $locator->locate('/opt/php/bin/php');
// /opt/php/bin/php

chdir('/opt/php');

echo $locator->locate('bin/php');
// /opt/php/bin/php
```

Finally, an additional `locateAll` method is included.  If a command exists at
multiple places on the `PATH`, this will return all of them.  It behaves all
the rules as the standard `locate` method.
```php
$locatorFactory = new \Nubs\Which\LocatorFactory\PlatformLocatorFactory();
$locator = $locatorFactory->create();

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

chdir('/opt/php');

var_dump($locator->locateAll('bin/php'));
// array(1) {
//   [0] =>
//   string(16) "/opt/php/bin/php"
// }
```

### CLI Interface
There is also a CLI interface for both POSIX systems and Windows that imitates
the standard which command.  It is available as
[`nubs/which-cli`](https://github.com/nubs/which-cli).

### Why?
I created which in order to fill a hole I came across: Detecting whether a user
has a certain command installed.  Primarily this was for
[sensible](https://github.com/nubs/sensible), a library that picks the user's
preferred editor/browser/pager and falls back to a sensible default if no
preference is specified.  which provides the ability for sensible to decide
whether the different command choices exist.  Read more about it on my
[blog](http://www.overthemonkey.com/blog/which-which-is-which).

### Similar Projects
I am also aware of a node.js library with a similar role:
[node-which](https://github.com/isaacs/node-which).
