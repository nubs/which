<?php
namespace Nubs\Which\Application;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * @coversDefaultClass \Nubs\Which\Application\WhichApplication
 */
class WhichApplicationTest extends PHPUnit_Framework_TestCase
{
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
        $locator = $this->getMockBuilder('\Nubs\Which\Locator')->disableOriginalConstructor()->setMethods(array('locate'))->getMock();
        $locator->expects($this->once())->method('locate')->with('foo')->will($this->returnValue('/path/to/foo'));

        $application = new WhichApplication($locator);
        $application->setAutoExit(false);
        $tester = new ApplicationTester($application);

        $tester->run(array('commands' => array('foo')));
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertSame("/path/to/foo\n", $tester->getDisplay());
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
        $locator = $this->getMockBuilder('\Nubs\Which\Locator')->disableOriginalConstructor()->setMethods(array('locate'))->getMock();
        $locator->expects($this->at(0))->method('locate')->with('foo')->will($this->returnValue('/path/to/foo'));
        $locator->expects($this->at(1))->method('locate')->with('bar')->will($this->returnValue('/path/to/bar'));

        $application = new WhichApplication($locator);
        $application->setAutoExit(false);
        $tester = new ApplicationTester($application);

        $tester->run(array('commands' => array('foo', 'bar')));
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertSame("/path/to/foo\n/path/to/bar\n", $tester->getDisplay());
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
        $locator = $this->getMockBuilder('\Nubs\Which\Locator')->disableOriginalConstructor()->setMethods(array('locate'))->getMock();
        $locator->expects($this->once())->method('locate')->with('foo')->will($this->returnValue(null));

        $application = new WhichApplication($locator);
        $application->setAutoExit(false);
        $tester = new ApplicationTester($application);

        $tester->run(array('commands' => array('foo')));
        $this->assertSame(1, $tester->getStatusCode());
        $this->assertSame("foo not found\n", $tester->getDisplay());
    }
}
