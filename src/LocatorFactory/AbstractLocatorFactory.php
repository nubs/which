<?php
namespace Nubs\Which\LocatorFactory;

use Habitat\Environment\Environment;

abstract class AbstractLocatorFactory
{
    /**
     * Create a locator using the PATH environment variable string.
     *
     * @api
     * @param string $path The PATH string.
     * @return \Nubs\Which\Locator The locator.
     */
    public abstract function createFromPath($path);

    /**
     * Create a locator from the PATH environment variable.
     *
     * @api
     * @param \Habitat\Environment\Environment $environment The environment
     *     variable wrapper.  Defaults to null which just uses PHP's built-in
     *     getenv.
     * @return \Nubs\Which\Locator The locator.
     */
    public function createFromEnvironment(Environment $environment = null)
    {
        return $this->createFromPath($environment ? $environment->getenv('PATH') : getenv('PATH'));
    }
}
