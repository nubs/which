<?php
namespace Nubs\Which\PathBuilder;

/**
 * Appends filenames to an array of paths.
 */
trait PosixFileAppenderTrait
{
    use FileAppenderTrait;

    /**
     * Joins two path segments together.
     *
     * @param string $start The starting segment.
     * @param string $end The ending segment.
     * @return string The joined segments.
     */
    protected function _joinPaths($start, $end)
    {
        return "{$start}/{$end}";
    }

    /**
     * Checks to see if a path is just a single atom (no directory).
     *
     * @param string $path The path to check.
     * @return boolean True if it's an atom, false otherwise.
     */
    protected function _isAtom($path)
    {
        return strpos($path, '/') === false;
    }
}
