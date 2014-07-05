<?php
namespace Nubs\Which\PathBuilder;

/**
 * A path builder for building paths to commands on Windows.
 */
class WindowsPathBuilder implements PathBuilderInterface
{
    use WindowsFileAppenderTrait;
    use WindowsExtensionAppenderTrait;

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
        $paths = $this->_appendFileToPaths($this->_paths, $file);

        return $this->_appendExtensionsToPaths($paths, $this->_extensions);
    }
}
