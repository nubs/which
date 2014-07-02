<?php
namespace Nubs\Which\PathBuilder;

/**
 * A path builder for building paths to commands on POSIXy systems (e.g., Linux,
 * OSX, BSD).
 */
class PosixPathBuilder implements PathBuilderInterface
{
    /** @type array The base paths for files. */
    private $_paths;

    /**
     * Initialize the path builder.
     *
     * @param array $paths The base paths for files.
     */ 
    public function __construct(array $paths)
    {
        $this->_paths = $paths;
    }

    /**
     * Gets the permutations of paths to the given file.
     *
     * @param string $file The file to build paths to.
     * @return array The permutations of file paths.
     */
    public function getPermutations($file)
    {
        if (!$this->_isAtom($file)) {
            return array($file);
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
    private function _joinPaths($start, $end)
    {
        return "{$start}/{$end}";
    }

    /**
     * Checks to see if a path is just a single atom (no directory).
     *
     * @param string $path The path to check.
     * @return boolean True if it's an atom, false otherwise.
     */
    private function _isAtom($path)
    {
        return strpos($path, '/') === false;
    }
}
