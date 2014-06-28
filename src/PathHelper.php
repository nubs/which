<?php
namespace Nubs\Which;

/**
 * Provides some helper methods for paths.
 */
class PathHelper
{
    /**
     * Joins two path segments together.
     *
     * @param string $start The starting segment.
     * @param string $end The ending segment.
     * @return string The joined segments.
     */
    public function joinPaths($start, $end)
    {
        return "{$start}/{$end}";
    }

    /**
     * Checks to see if a path is just a single atom (no directory).
     *
     * @param string $path The path to check.
     * @return boolean True if it's an atom, false otherwise.
     */
    public function isAtom($path)
    {
        return strpos($path, '/') === false;
    }

    /**
     * Checks to see if a path is an absolute path.
     *
     * @param string $path The path to check.
     * @return boolean True if it's an absolute path, false if relative.
     */
    public function isAbsolute($path)
    {
        return $path !== '' && $path[0] === '/';
    }
}
