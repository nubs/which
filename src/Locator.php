<?php
namespace Nubs\Which;

use Nubs\Which\PathBuilder\PathBuilderInterface;

/**
 * Provides the ability to locate commands in the user's PATH.
 */
class Locator
{
    /** @type \Nubs\Which\PathBuilder\PathBuilderInterface The path builder. */
    private $_pathBuilder;

    /** @type \Nubs\Which\ExecutableTester The executable tester. */
    private $_executableTester;

    /**
     * Initialize the locator.
     *
     * @api
     * @param \Nubs\Which\PathBuilder\PathBuilderInterface $pathBuilder The path
     *     builder.
     * @param \Nubs\Which\ExecutableTester $executableTester The executable
     *     tester.
     */
    public function __construct(PathBuilderInterface $pathBuilder, ExecutableTester $executableTester = null)
    {
        $this->_pathBuilder = $pathBuilder;
        $this->_executableTester = $executableTester ?: new ExecutableTester();
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
        return array_values(array_unique(array_filter($this->_pathBuilder->getPermutations($command), $this->_executableTester)));
    }
}
