<?php
namespace Nubs\Which\LocatorFactory;

use Habitat\Environment\Environment;

/**
 * An interface for a Locator Factory.
 */
interface LocatorFactoryInterface
{
    /**
     * Create a locator using the environment.
     *
     * @api
     * @param \Habitat\Environment\Environment $environment The environment
     *     variable wrapper.  Defaults to null which just uses PHP's built-in
     *     getenv.
     * @return \Nubs\Which\Locator The locator.
     */
    public function createFromEnvironment(Environment $environment = null);
}
