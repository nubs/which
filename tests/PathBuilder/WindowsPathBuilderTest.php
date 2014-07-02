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
        $this->_windowsPathBuilder = new WindowsPathBuilder(array('C:\\foo', 'C:\\baz'), array('.exe', '.com'));
    }

    /**
     * Verify that getPermutations works.
     *
     * @test
     * @covers ::__construct
     * @covers ::getPermutations
     * @covers ::_joinPaths
     * @covers ::_addExtension
     * @covers ::_isAtom
     * @covers ::_hasExtension
     */
    public function getPermutations()
    {
        $this->assertSame(
            array('C:\\foo\\bar.exe', 'C:\\foo\\bar.com', 'C:\\baz\\bar.exe', 'C:\\baz\\bar.com'),
            $this->_windowsPathBuilder->getPermutations('bar')
        );
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

    /**
     * Verify that getPermutations works with an extension specified.
     *
     * @test
     * @covers ::__construct
     * @covers ::getPermutations
     * @covers ::_joinPaths
     * @covers ::_isAtom
     * @covers ::_hasExtension
     */
    public function getPermutationsWithExtension()
    {
        $this->assertSame(array('C:\\foo\\bar.qux', 'C:\\baz\\bar.qux'), $this->_windowsPathBuilder->getPermutations('bar.qux'));
    }
}
