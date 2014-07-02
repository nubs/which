<?php
namespace Nubs\Which\PathBuilder;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\PathBuilder\PosixPathBuilder
 */
class PosixPathBuilderTest extends PHPUnit_Framework_TestCase
{
    private $_posixPathBuilder;

    public function setUp()
    {
        $this->_posixPathBuilder = new PosixPathBuilder(['/foo', '/baz']);
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
        $this->assertSame(['/foo/bar', '/baz/bar'], $this->_posixPathBuilder->getPermutations('bar'));
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
        $this->assertSame(['/qux/bar'], $this->_posixPathBuilder->getPermutations('/qux/bar'));
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
        $this->assertSame(['qux/bar'], $this->_posixPathBuilder->getPermutations('qux/bar'));
    }
}
