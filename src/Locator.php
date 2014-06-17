<?php
namespace Nubs\Which;

use Habitat\Environment\Environment;

/**
 * Provides the ability to locate commands in the user's PATH.
 */
class Locator
{
    /**
     * Initialize the locator.
     *
     * @api
     * @param array $paths The possible paths to commands.
     */
    public function __construct(array $paths)
    {
        $this->_paths = $paths;
    }

    /**
     * Factory method to create a locator using a colon-separated PATH string.
     *
     * The colon is a hard separator.  This means that command paths cannot have
     * a colon in the name.
     *
     * @api
     * @param string $path The colon-separated PATH string.
     * @return self The locator.
     */
    public static function createFromPathEnvironmentVariable($path)
    {
        return new static(array_filter(explode(':', $path)));
    }

    /**
     * Factory method to create a locator from the PATH environment variable.
     *
     * @api
     * @param \Habitat\Environment\Environment $environment The environment
     *     variable wrapper.  Defaults to null which just uses the built-in
     *     getenv.
     * @return self The locator.
     */
    public static function createFromEnvironment(Environment $environment = null)
    {
        return static::createFromPathEnvironmentVariable($environment ? $environment->getenv('PATH') : getenv('PATH'));
    }

    /**
     * Locates the given command in the user's path.
     *
     * @api
     * @param string $command The command to locate.
     * @return string|null The full path to the command or null if not path
     *     found.
     */
    public function locate($command)
    {
        $paths = $this->locateAll($command);

        return empty($paths) ? null : $paths[0];
    }

    /**
     * Locates all versions of the given command in the user's path.
     *
     * @api
     * @param string $command The command to locate.
     * @return array The locations where the command exists.
     */
    public function locateAll($command)
    {
        if (!$this->_isValidCommandName($command)) {
            return array();
        }

        return array_values(array_unique(array_filter($this->_getPotentialCommandLocations($command), 'is_executable')));
    }

    /**
     * Gets the full paths to check for the command.
     *
     * @param string $command The command to locate.
     * @return array The potential paths where the command may exist.
     */
    protected function _getPotentialCommandLocations($command)
    {
        $getCommandPath = function($path) use($command) {
            return "{$path}/{$command}";
        };

        return array_map($getCommandPath, $this->_getPaths());
    }

    /**
     * Gets the paths where commands exist.
     *
     * @return array The paths where commands may exist.
     */
    protected function _getPaths()
    {
        return $this->_paths;
    }

    /**
     * Checks to see if the command (basename) is an allowed name for a command.
     *
     * @param string $command The command name to check.
     * @return bool The validity of the command name.
     */
    protected function _isValidCommandName($command)
    {
        return strpos($command, '/') === false && $command !== '.' && $command !== '..';
    }
}
