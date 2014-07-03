<?php
namespace Nubs\Which\LocatorFactory;

use Habitat\Environment\Environment;
use Nubs\Which\Locator;
use Nubs\Which\PathBuilder\WindowsPathBuilder;

/**
 * Locator factory for Windows systems.
 */
class WindowsLocatorFactory implements LocatorFactoryInterface
{
    /**
     * Create a locator using semicolon-separated PATH and PATHEXT strings.
     *
     * The semicolon is a hard separator.  This means that command paths cannot
     * have a semicolon in the name.
     *
     * The current working directory is always prepended to the list of paths.
     *
     * @api
     * @param string $path The semicolon-separated PATH string.
     * @param string $pathext The semicolon-separated PATHEXT string.
     * @return \Nubs\Which\Locator The locator.
     */
    public function createFromPath($path, $pathext)
    {
        $paths = array_merge(['.'], array_filter(explode(';', $path)));
        $extensions = array_merge([''], array_filter(explode(';', $pathext)));
        return new Locator(new WindowsPathBuilder($paths, $extensions));
    }

    /**
     * Create a locator from the PATH and PATHEXT environment variables.
     *
     * @api
     * @param \Habitat\Environment\Environment $environment The environment
     *     variable wrapper.  Defaults to null which just uses PHP's built-in
     *     getenv.
     * @return \Nubs\Which\Locator The locator.
     */
    public function createFromEnvironment(Environment $environment = null)
    {
        return $this->createFromPath(
            $environment ? $environment->getenv('PATH') : getenv('PATH'),
            $environment ? $environment->getenv('PATHEXT') : getenv('PATHEXT')
        );
    }
}
