<?php
namespace Nubs\Which\Application;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * @coversDefaultClass \Nubs\Which\Application\WhichApplication
 */
class WhichApplicationTest extends PHPUnit_Framework_TestCase
{
    private $_locator;

    private $_applicationTester;

    public function setUp()
    {
        $this->_locator = $this->getMockBuilder('\Nubs\Which\Locator')->disableOriginalConstructor()->setMethods(['locate'])->getMock();

        $application = new WhichApplication($this->_locator);
        $application->setAutoExit(false);
        $this->_applicationTester = new ApplicationTester($application);
    }

    /**
     * Verify that a simple call works.
     *
     * @test
     * @covers ::__construct
     * @covers ::getCommandName
     * @covers ::getDefaultCommands
     * @covers ::getDefinition
     * @covers ::getLocator
     * @covers \Nubs\Which\Application\WhichCommand
     */
    public function simpleCall()
    {
        $this->_locator->expects($this->once())->method('locate')->with('foo')->will($this->returnValue('/path/to/foo'));

        $this->_applicationTester->run(['commands' => ['foo']]);
        $this->assertSame(0, $this->_applicationTester->getStatusCode());
        $this->assertSame("/path/to/foo\n", $this->_applicationTester->getDisplay());
    }

    /**
     * Verify that a multi-command call works.
     *
     * @test
     * @covers ::__construct
     * @covers ::getCommandName
     * @covers ::getDefaultCommands
     * @covers ::getDefinition
     * @covers ::getLocator
     * @covers \Nubs\Which\Application\WhichCommand
     */
    public function multiCall()
    {
        $this->_locator->expects($this->at(0))->method('locate')->with('foo')->will($this->returnValue('/path/to/foo'));
        $this->_locator->expects($this->at(1))->method('locate')->with('bar')->will($this->returnValue('/path/to/bar'));

        $this->_applicationTester->run(['commands' => ['foo', 'bar']]);
        $this->assertSame(0, $this->_applicationTester->getStatusCode());
        $this->assertSame("/path/to/foo\n/path/to/bar\n", $this->_applicationTester->getDisplay());
    }

    /**
     * Verify that a check for a nonexistant command works.
     *
     * @test
     * @covers ::__construct
     * @covers ::getCommandName
     * @covers ::getDefaultCommands
     * @covers ::getDefinition
     * @covers ::getLocator
     * @covers \Nubs\Which\Application\WhichCommand
     */
    public function nonexistantCommand()
    {
        $this->_locator->expects($this->once())->method('locate')->with('foo')->will($this->returnValue(null));

        $this->_applicationTester->run(['commands' => ['foo']]);
        $this->assertSame(1, $this->_applicationTester->getStatusCode());
        $this->assertSame("foo not found\n", $this->_applicationTester->getDisplay());
    }
}
