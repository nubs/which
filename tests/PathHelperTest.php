<?php
namespace Nubs\Which;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\PathHelper
 */
class PathHelperTest extends PHPUnit_Framework_TestCase
{
    private $_pathHelper;

    public function setUp()
    {
        $this->_pathHelper = new PathHelper();
    }

    /**
     * Verify that join paths works.
     *
     * @test
     * @covers ::joinPaths
     */
    public function joinPaths()
    {
        $this->assertSame('/foo/bar', $this->_pathHelper->joinPaths('/foo', 'bar'));
    }

    /**
     * Verify that isAtom works with an atom.
     *
     * @test
     * @covers ::isAtom
     */
    public function isAtomWithAtom()
    {
        $this->assertTrue($this->_pathHelper->isAtom('foo'));
    }

    /**
     * Verify that isAtom works with a sub directory.
     *
     * @test
     * @covers ::isAtom
     */
    public function isAtomWithSubdirectory()
    {
        $this->assertFalse($this->_pathHelper->isAtom('foo/bar'));
    }

    /**
     * Verify that isAbsolute works with an absolute path.
     *
     * @test
     * @covers ::isAbsolute
     */
    public function isAbsoluteWithAbsolute()
    {
        $this->assertTrue($this->_pathHelper->isAbsolute('/foo/bar'));
    }

    /**
     * Verify that isAbsolute works with a sub directory.
     *
     * @test
     * @covers ::isAbsolute
     */
    public function isAbsoluteWithSubdirectory()
    {
        $this->assertFalse($this->_pathHelper->isAbsolute('foo/bar'));
    }
}
