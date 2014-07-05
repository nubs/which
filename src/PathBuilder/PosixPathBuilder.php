<?php
namespace Nubs\Which\PathBuilder;

/**
 * A path builder for building paths to commands on POSIXy systems (e.g., Linux,
 * OSX, BSD).
 */
class PosixPathBuilder implements PathBuilderInterface
{
    use PosixFileAppenderTrait;

    /** @type array The base paths for files. */
    private $_paths;

    /**
     * Initialize the path builder.
     *
     * @param array $paths The base paths for files.
     */ 
    public function __construct(array $paths)
    {
        $this->_paths = array_values(array_unique($paths));
    }

    /**
     * Gets the permutations of paths to the given file.
     *
     * @param string $file The file to build paths to.
     * @return array The permutations of file paths.
     */
    public function getPermutations($file)
    {
        return $this->_appendFileToPaths($this->_paths, $file);
    }
}
