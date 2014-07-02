<?php
namespace Nubs\Which\LocatorFactory;

use Icecave\Isolator\Isolator;

/**
 * Factory that uses the current platform to determine whether to use the
 * PosixLocatorFactory or the WindowsLocatorFactory.
 */
class PlatformLocatorFactory extends AbstractLocatorFactory
{
    /** @type \Nubs\Which\LocatorFactory\AbstractLocatorFactory The platform-specific factory. */
    private $_platformFactory;

    /**
     * Creates the factory to wrap the platform-specific factory.
     *
     * @api
     * @param \Icecave\Isolator\Isolator $isolator The isolator object to
     *     override environment variable lookup.
     */
    public function  __construct(Isolator $isolator = null)
    {
        if ($isolator ? $isolator->defined('PHP_WINDOWS_VERSION_BUILD') : defined('PHP_WINDOWS_VERSION_BUILD')) {
            $this->_platformFactory = new WindowsLocatorFactory();
        } else {
            $this->_platformFactory = new PosixLocatorFactory();
        }
    }

    /**
     * Create a locator using the platform-specific factory.
     *
     * @api
     * @param string $path The PATH string.
     * @return \Nubs\Which\Locator The locator.
     */
    public function createFromPath($path)
    {
        return $this->_platformFactory->createFromPath($path);
    }
}
