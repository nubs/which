<?php
namespace Nubs\Which;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\PathHelper
 */
class PathHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that join paths works.
     *
     * @test
     * @covers ::joinPaths
     */
    public function joinPaths()
    {
        $pathHelper = new PathHelper();
        $this->assertSame('/foo/bar', $pathHelper->joinPaths('/foo', 'bar'));
    }

    /**
     * Verify that isAtom works with an atom.
     *
     * @test
     * @covers ::isAtom
     */
    public function isAtomWithAtom()
    {
        $pathHelper = new PathHelper();
        $this->assertTrue($pathHelper->isAtom('foo'));
    }

    /**
     * Verify that isAtom works with a sub directory.
     *
     * @test
     * @covers ::isAtom
     */
    public function isAtomWithSubdirectory()
    {
        $pathHelper = new PathHelper();
        $this->assertFalse($pathHelper->isAtom('foo/bar'));
    }

    /**
     * Verify that isAbsolute works with an absolute path.
     *
     * @test
     * @covers ::isAbsolute
     */
    public function isAbsoluteWithAbsolute()
    {
        $pathHelper = new PathHelper();
        $this->assertTrue($pathHelper->isAbsolute('/foo/bar'));
    }

    /**
     * Verify that isAbsolute works with a sub directory.
     *
     * @test
     * @covers ::isAbsolute
     */
    public function isAbsoluteWithSubdirectory()
    {
        $pathHelper = new PathHelper();
        $this->assertFalse($pathHelper->isAbsolute('foo/bar'));
    }
}
