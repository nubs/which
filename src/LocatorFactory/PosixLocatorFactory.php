<?php
namespace Nubs\Which\LocatorFactory;

use Nubs\Which\Locator;

class PosixLocatorFactory extends AbstractLocatorFactory
{
    /**
     * Create a locator using a colon-separated PATH string.
     *
     * The colon is a hard separator.  This means that command paths cannot have
     * a colon in the name.
     *
     * @api
     * @param string $path The colon-separated PATH string.
     * @return \Nubs\Which\Locator The locator.
     */
    public function createFromPath($path)
    {
        return new Locator(array_filter(explode(':', $path)));
    }
}
