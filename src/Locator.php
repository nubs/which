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
    protected $_executableTester;

    /**
     * Initialize the locator.
     *
     * @api
     * @param \Nubs\Which\PathBuilder\PathBuilderInterface $pathBuilder The path
     *     builder.
     */
    public function __construct(PathBuilderInterface $pathBuilder)
    {
        $this->_pathBuilder = $pathBuilder;
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
        return array_values(array_unique(array_filter($this->_pathBuilder->getPermutations($command), $this->executableTester())));
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
}
