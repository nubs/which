<?php
namespace Nubs\Which\PathBuilder;

/**
 * Appends filenames to an array of paths.
 */
trait FileAppenderTrait
{
    /**
     * Append the filename to the array of paths.
     *
     * @param array $paths The paths to append to.
     * @param string $file The filename.
     * @return array The paths with the file name appended.
     */
    protected function _appendFileToPaths(array $paths, $file)
    {
        if (!$this->_isAtom($file)) {
            return [$file];
        }

        $appendFile = function($path) use($file) {
            return $this->_joinPaths($path, $file);
        };

        return array_map($appendFile, $this->_paths);
    }

    /**
     * Joins two path segments together.
     *
     * @param string $start The starting segment.
     * @param string $end The ending segment.
     * @return string The joined segments.
     */
    abstract protected function _joinPaths($path, $file);

    /**
     * Checks to see if a path is just a single atom (no directory).
     *
     * @param string $path The path to check.
     * @return boolean True if it's an atom, false otherwise.
     */
    abstract protected function _isAtom($path, $file);
}
