<?php
namespace Nubs\Which\LocatorFactory;

use Habitat\Environment\Environment;
use Nubs\Which\Locator;
use Nubs\Which\PathBuilder\PosixPathBuilder;

/**
 * Locator factory for POSIXy systems (e.g. Linux, OSX, BSD).
 */
class PosixLocatorFactory implements LocatorFactoryInterface
{
    /**
     * Create a locator from the PATH environment variable.
     *
     * @api
     * @param \Habitat\Environment\Environment $environment The environment
     *     variable wrapper.  Defaults to null which just uses PHP's built-in
     *     getenv.
     * @return \Nubs\Which\Locator The locator.
     */
    public function create(Environment $environment = null)
    {
        return $this->createFromPath($environment ? $environment->getenv('PATH') : getenv('PATH'));
    }

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
        return new Locator(new PosixPathBuilder(array_filter(explode(':', $path))));
    }
}
