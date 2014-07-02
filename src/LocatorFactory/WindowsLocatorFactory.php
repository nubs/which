<?php
namespace Nubs\Which\LocatorFactory;

use Nubs\Which\Locator;
use Nubs\Which\PathBuilder\WindowsPathBuilder;

/**
 * Locator factory for Windows systems.
 */
class WindowsLocatorFactory extends AbstractLocatorFactory
{
    /**
     * Create a locator using a semicolon-separated PATH string.
     *
     * The semicolon is a hard separator.  This means that command paths cannot
     * have a semicolon in the name.
     *
     * The current working directory is always prepended to the list of paths.
     *
     * @api
     * @param string $path The semicolon-separated PATH string.
     * @return \Nubs\Which\Locator The locator.
     */
    public function createFromPath($path)
    {
        return new Locator(new WindowsPathBuilder(array_merge(array('.'), array_filter(explode(';', $path)))));
    }
}
