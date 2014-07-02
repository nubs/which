<?php
namespace Nubs\Which;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\Locator
 */
class LocatorTest extends PHPUnit_Framework_TestCase
{
    private $_pathBuilder;

    private $_executableTester;

    private $_locator;

    public function setUp()
    {
        $this->_pathBuilder = $this->getMockBuilder('\Nubs\Which\PathBuilder\PathBuilderInterface')->setMethods(array('getPermutations'))->getMock();
        $this->_executableTester = $this->getMockBuilder('\Nubs\Which\ExecutableTester')->disableOriginalConstructor()->setMethods(array('__invoke'))->getMock();
        $this->_locator = new Locator($this->_pathBuilder);
        $this->_locator->setExecutableTester($this->_executableTester);
    }

    /**
     * Verify that a simple command works.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::setExecutableTester
     * @covers ::executableTester
     */
    public function locateSimpleCommand()
    {
        $this->_pathBuilder->expects($this->once())->method('getPermutations')->with('bar')->will($this->returnValue(array('/foo/bar')));
        $this->_executableTester->expects($this->once())->method('__invoke')->with('/foo/bar')->will($this->returnValue(true));

        $this->assertSame('/foo/bar', $this->_locator->locate('bar'));
    }

    /**
     * Verify that a non-executable path does not get returned.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::setExecutableTester
     * @covers ::executableTester
     */
    public function locateNonExecutableCommand()
    {
        $this->_pathBuilder->expects($this->once())->method('getPermutations')->with('bar')->will($this->returnValue(array('/foo/bar')));
        $this->_executableTester->expects($this->once())->method('__invoke')->with('/foo/bar')->will($this->returnValue(false));

        $this->assertNull($this->_locator->locate('bar'));
    }

    /**
     * Verify that locate returns the first occurence of a command.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::setExecutableTester
     * @covers ::executableTester
     */
    public function locateMultipleLocations()
    {
        $this->_pathBuilder->expects($this->once())->method('getPermutations')->with('bar')->will($this->returnValue(array('/foo/bar', '/baz/bar')));
        $this->_executableTester->expects($this->at(0))->method('__invoke')->with('/foo/bar')->will($this->returnValue(true));
        $this->_executableTester->expects($this->at(1))->method('__invoke')->with('/baz/bar')->will($this->returnValue(true));

        $locator = new Locator($this->_pathBuilder);
        $locator->setExecutableTester($this->_executableTester);

        $this->assertSame('/foo/bar', $locator->locate('bar'));
    }

    /**
     * Verify that locateAll returns all occurences of a command.
     *
     * @test
     * @covers ::__construct
     * @covers ::locateAll
     * @covers ::setExecutableTester
     * @covers ::executableTester
     */
    public function locateAll()
    {
        $this->_pathBuilder->expects($this->once())->method('getPermutations')->with('bar')->will($this->returnValue(array('/foo/bar', '/baz/bar')));
        $this->_executableTester->expects($this->at(0))->method('__invoke')->with('/foo/bar')->will($this->returnValue(true));
        $this->_executableTester->expects($this->at(1))->method('__invoke')->with('/baz/bar')->will($this->returnValue(true));

        $locator = new Locator($this->_pathBuilder);
        $locator->setExecutableTester($this->_executableTester);

        $this->assertSame(array('/foo/bar', '/baz/bar'), $locator->locateAll('bar'));
    }
}
