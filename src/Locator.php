<?php
namespace Nubs\Which;

use Nubs\Which\PathHelper\PathHelperInterface;
use Nubs\Which\PathHelper\PosixPathHelper;

/**
 * Provides the ability to locate commands in the user's PATH.
 */
class Locator
{
    /** @type array The possible paths to commands. */
    protected $_paths;

    /** @type \Nubs\Which\PathHelper\PathHelperInterface The path helper. */
    protected $_pathHelper;

    /** @type \Nubs\Which\ExecutableTester The executable tester. */
    protected $_executableTester;

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
        return array_values(array_unique(array_filter($this->_getPotentialCommandLocations($command), $this->executableTester())));
    }

    /**
     * Override the default path helper.
     *
     * @param \Nubs\Which\PathHelper\PathHelperInterface $pathHelper The path
     *     helper.
     * @return void
     */
    public function setPathHelper(PathHelperInterface $pathHelper)
    {
        $this->_pathHelper = $pathHelper;
    }

    /**
     * Get the path helper.
     *
     * @return \Nubs\Which\PathHelper\PathHelperInterface The path helper.
     */
    public function pathHelper()
    {
        $this->_pathHelper = $this->_pathHelper ?: new PosixPathHelper();

        return $this->_pathHelper;
    }

    /**
     * Override the default executable tester.
     *
     * @param \Nubs\Which\ExecutableTester $executableTester The executable
     *     tester.
     * @return void
     */
    public function setExecutableTester(ExecutableTester $executableTester)
    {
        $this->_executableTester = $executableTester;
    }

    /**
     * Get the executable tester.
     *
     * @return \Nubs\Which\ExecutableTester The executable tester.
     */
    public function executableTester()
    {
        $this->_executableTester = $this->_executableTester ?: new ExecutableTester();

        return $this->_executableTester;
    }

    /**
     * Gets the full paths to check for the command.
     *
     * @param string $command The command to locate.
     * @return array The potential paths where the command may exist.
     */
    protected function _getPotentialCommandLocations($command)
    {
        $pathHelper = $this->pathHelper();

        if (!$pathHelper->isAtom($command)) {
            return array($command);
        }

        $appendCommand = function($path) use($command, $pathHelper) {
            return $pathHelper->joinPaths($path, $command);
        };

        return array_map($appendCommand, $this->_getPaths());
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
}
