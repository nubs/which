<?php
namespace Nubs\Which\PathBuilder;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\PathBuilder\WindowsPathBuilder
 */
class WindowsPathBuilderTest extends PHPUnit_Framework_TestCase
{
    private $_windowsPathBuilder;

    public function setUp()
    {
        $this->_windowsPathBuilder = new WindowsPathBuilder(array('C:\\foo', 'C:\\baz'));
    }

    /**
     * Verify that getPermutations works.
     *
     * @test
     * @covers ::__construct
     * @covers ::getPermutations
     * @covers ::_joinPaths
     * @covers ::_isAtom
     */
    public function getPermutations()
    {
        $this->assertSame(array('C:\\foo\\bar', 'C:\\baz\\bar'), $this->_windowsPathBuilder->getPermutations('bar'));
    }

    /**
     * Verify that getPermutations works with an absolute path.
     *
     * @test
     * @covers ::__construct
     * @covers ::getPermutations
     * @covers ::_joinPaths
     * @covers ::_isAtom
     */
    public function getPermutationsForAbsolutePath()
    {
        $this->assertSame(array('C:\\qux\\bar'), $this->_windowsPathBuilder->getPermutations('C:\\qux\\bar'));
    }

    /**
     * Verify that getPermutations works with a subdirectory.
     *
     * @test
     * @covers ::__construct
     * @covers ::getPermutations
     * @covers ::_joinPaths
     * @covers ::_isAtom
     */
    public function getPermutationsForSubdirectory()
    {
        $this->assertSame(array('qux\\bar'), $this->_windowsPathBuilder->getPermutations('qux\\bar'));
    }
}
