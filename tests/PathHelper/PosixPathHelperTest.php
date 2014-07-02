<?php
namespace Nubs\Which\PathHelper;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\PathHelper\PosixPathHelper
 */
class PosixPathHelperTest extends PHPUnit_Framework_TestCase
{
    private $_posixPathHelper;

    public function setUp()
    {
        $this->_posixPathHelper = new PosixPathHelper();
    }

    /**
     * Verify that join paths works.
     *
     * @test
     * @covers ::joinPaths
     */
    public function joinPaths()
    {
        $this->assertSame('/foo/bar', $this->_posixPathHelper->joinPaths('/foo', 'bar'));
    }

    /**
     * Verify that isAtom works with an atom.
     *
     * @test
     * @covers ::isAtom
     */
    public function isAtomWithAtom()
    {
        $this->assertTrue($this->_posixPathHelper->isAtom('foo'));
    }

    /**
     * Verify that isAtom works with a sub directory.
     *
     * @test
     * @covers ::isAtom
     */
    public function isAtomWithSubdirectory()
    {
        $this->assertFalse($this->_posixPathHelper->isAtom('foo/bar'));
    }
}
