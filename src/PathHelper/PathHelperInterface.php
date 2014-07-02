<?php
namespace Nubs\Which\PathHelper;

/**
 * Helper methods for dealing with paths.
 */
interface PathHelperInterface
{
    /**
     * Joins two path segments together.
     *
     * @param string $start The starting segment.
     * @param string $end The ending segment.
     * @return string The joined segments.
     */
    public function joinPaths($start, $end);

    /**
     * Checks to see if a path is just a single atom (no directory).
     *
     * @param string $path The path to check.
     * @return boolean True if it's an atom, false otherwise.
     */
    public function isAtom($path);
}
