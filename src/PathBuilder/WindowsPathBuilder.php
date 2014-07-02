<?php
namespace Nubs\Which\PathBuilder;

/**
 * A path builder for building paths to commands on Windows.
 */
class WindowsPathBuilder implements PathBuilderInterface
{
    /** @type array The base paths for files. */
    private $_paths;

    /** @type array The supported extensions for files. */
    private $_extensions;

    /**
     * Initialize the path builder.
     *
     * @param array $paths The base paths for files.
     * @param array $extensions The supported extensions for files.
     */ 
    public function __construct(array $paths, array $extensions)
    {
        $this->_paths = array_values(array_unique($paths));
        $this->_extensions = array_values(array_unique($extensions));
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
            return [$file];
        }

        $hasExtension = $this->_hasExtension($file);
        $appendFile = function($path) use($file, $hasExtension) {
            $fullPath = $this->_joinPaths($path, $file);

            if ($hasExtension) {
                return [$fullPath];
            }

            $addExtension = function($extension) use ($fullPath) {
                return $this->_addExtension($fullPath, $extension);
            };

            return array_map($addExtension, $this->_extensions);
        };

        return call_user_func_array('array_merge', array_map($appendFile, $this->_paths));
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
        return "{$start}\\{$end}";
    }

    /**
     * Adds an extension to a path.
     *
     * @param string $path The path.
     * @param string $extension The extension to add.
     * @return string The path with extension.
     */
    private function _addExtension($path, $extension)
    {
        return $path . $extension;
    }

    /**
     * Checks to see if a path is just a single atom (no directory).
     *
     * @param string $path The path to check.
     * @return boolean True if it's an atom, false otherwise.
     */
    private function _isAtom($path)
    {
        return strpos($path, '\\') === false;
    }

    /**
     * Checks to see if a path already has an extension.
     *
     * @param string $path The path to check.
     * @return boolean True if it has an extension, false otherwise.
     */
    private function _hasExtension($path)
    {
        return strpos($path, '.') !== false;
    }
}
