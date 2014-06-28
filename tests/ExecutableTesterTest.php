<?php
namespace Nubs\Which;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\ExecutableTester
 */
class ExecutableTesterTest extends PHPUnit_Framework_TestCase
{
    private $_isolator;

    public function setUp()
    {
        $this->_isolator = $this->getMockBuilder('\Icecave\Isolator\Isolator')->disableOriginalConstructor()->setMethods(array('is_executable', 'is_dir'))->getMock();
    }

    /**
     * Verify that an executable file returns true.
     *
     * @test
     * @covers ::__construct
     * @covers ::__invoke
     */
    public function executableFile()
    {
        $this->_isolator->expects($this->once())->method('is_executable')->with('/foo/bar')->will($this->returnValue(true));
        $this->_isolator->expects($this->once())->method('is_dir')->with('/foo/bar')->will($this->returnValue(false));

        $executableTester = new ExecutableTester($this->_isolator);
        $this->assertTrue($executableTester('/foo/bar'));
    }

    /**
     * Verify that a nonexecutable file returns false.
     *
     * @test
     * @covers ::__construct
     * @covers ::__invoke
     */
    public function nonexecutableFile()
    {
        $this->_isolator->expects($this->once())->method('is_executable')->with('/foo/bar')->will($this->returnValue(false));
        $this->_isolator->expects($this->once())->method('is_dir')->with('/foo/bar')->will($this->returnValue(false));

        $executableTester = new ExecutableTester($this->_isolator);
        $this->assertFalse($executableTester('/foo/bar'));
    }

    /**
     * Verify that a directory returns false.
     *
     * @test
     * @covers ::__construct
     * @covers ::__invoke
     */
    public function directory()
    {
        $this->_isolator->expects($this->once())->method('is_executable')->with('/foo/bar')->will($this->returnValue(true));
        $this->_isolator->expects($this->once())->method('is_dir')->with('/foo/bar')->will($this->returnValue(true));

        $executableTester = new ExecutableTester($this->_isolator);
        $this->assertFalse($executableTester('/foo/bar'));
    }
}
