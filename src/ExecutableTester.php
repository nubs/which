<?php
namespace Nubs\Which;

use Icecave\Isolator\Isolator;

/**
 * Tests paths to see if they are executable.
 */
class ExecutableTester
{
    /** @type \Icecave\Isolator\Isolator The DIC for global functions. */
    private $_isolator;

    /**
     * Initialize the executable tester.
     *
     * @param \Icecave\Isolator\Isolator The DIC for global functions.
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->_isolator = $isolator;
    }

    /**
     * Tests the given path to see if it is an executable command.
     *
     * @param string $path The path to a command to test.
     * @return boolean True if the path is an executable command, false
     *     otherwise.
     */
    public function __invoke($path)
    {
        $isExecutable = $this->_isolator ? $this->_isolator->is_executable($path) : is_executable($path);
        $isDir = $this->_isolator ? $this->_isolator->is_dir($path) : is_dir($path);

        return $isExecutable && !$isDir;
    }
}
