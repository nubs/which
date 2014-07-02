<?php
namespace Nubs\Which\PathBuilder;

/**
 * A path builder for building paths to commands.
 */
interface PathBuilderInterface
{
    /**
     * Gets the permutations of paths to the given file.
     *
     * @param string $file The file to build paths to.
     * @return array The permutations of file paths.
     */
    public function getPermutations($file);
}
