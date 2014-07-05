<?php
namespace Nubs\Which\PathBuilder;

/**
 * Appends extensions to an array of files.
 */
trait WindowsExtensionAppenderTrait
{
    /**
     * Append the extensions to the paths.
     *
     * @param array $paths The file paths to append to.
     * @param array $extensions The extensions.
     * @return array The full combination of every path with every extension.
     */
    protected function _appendExtensionsToPaths(array $paths, array $extensions)
    {
        $getPathsWithExtensions = function($path) use($extensions) {
            if ($this->_hasExtension($path)) {
                return [$path];
            }

            $addExtension = function($extension) use($path) {
                return $this->_addExtension($path, $extension);
            };

            return array_map($addExtension, $extensions);
        };

        return call_user_func_array('array_merge', array_map($getPathsWithExtensions, $paths));
    }

    /**
     * Adds an extension to a path.
     *
     * @param string $path The path.
     * @param string $extension The extension to add.
     * @return string The path with extension.
     */
    protected function _addExtension($path, $extension)
    {
        return $path . $extension;
    }

    /**
     * Checks to see if a path already has an extension.
     *
     * @param string $path The path to check.
     * @return boolean True if it has an extension, false otherwise.
     */
    protected function _hasExtension($path)
    {
        return strpos($path, '.') !== false;
    }
}
