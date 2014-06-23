# Which
A PHP library for locating commands in a PATH.

[![Build Status](https://travis-ci.org/nubs/which.png)](https://travis-ci.org/nubs/which)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nubs/which/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nubs/which/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nubs/which/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nubs/which/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/nubs/which/v/stable.png)](https://packagist.org/packages/nubs/which)
[![Total Downloads](https://poser.pugx.org/nubs/which/downloads.png)](https://packagist.org/packages/nubs/which)
[![Latest Unstable Version](https://poser.pugx.org/nubs/which/v/unstable.png)](https://packagist.org/packages/nubs/which)
[![License](https://poser.pugx.org/nubs/which/license.png)](https://packagist.org/packages/nubs/which)

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

If you'd prefer not to add another external dependency, you can also construct
the locator using the `PATH` environment variable:
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
