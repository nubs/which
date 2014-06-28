<?php
namespace Nubs\Which;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\Locator
 */
class LocatorTest extends PHPUnit_Framework_TestCase
{
    private $_executableTester;

    private $_locator;

    public function setUp()
    {
        $this->_executableTester = $this->getMockBuilder('\Nubs\Which\ExecutableTester')->disableOriginalConstructor()->setMethods(array('__invoke'))->getMock();
        $this->_locator = new Locator(array('/foo'));
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
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateSimpleCommand()
    {
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
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateNonExecutableCommand()
    {
        $this->_executableTester->expects($this->once())->method('__invoke')->with('/foo/bar')->will($this->returnValue(false));

        $this->assertNull($this->_locator->locate('bar'));
    }

    /**
     * Verify that an absolute path to a command works.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::setExecutableTester
     * @covers ::executableTester
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateAbsoluteCommand()
    {
        $this->_executableTester->expects($this->once())->method('__invoke')->with('/foo/bar')->will($this->returnValue(true));

        $this->assertSame('/foo/bar', $this->_locator->locate('/foo/bar'));
    }

    /**
     * Verify that a command name that is in a subdirectory returns no result.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::setExecutableTester
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateSubdirectoryCommand()
    {
        $this->assertNull($this->_locator->locate('foo/bar'));
    }

    /**
     * Verify that a command name that is empty returns null.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::setExecutableTester
     * @covers ::executableTester
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateEmptyCommand()
    {
        $this->assertNull($this->_locator->locate(''));
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
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateMultipleLocations()
    {
        $this->_executableTester->expects($this->at(0))->method('__invoke')->with('/foo/bar')->will($this->returnValue(true));
        $this->_executableTester->expects($this->at(1))->method('__invoke')->with('/baz/bar')->will($this->returnValue(true));

        $locator = new Locator(array('/foo', '/baz'));
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
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateAll()
    {
        $this->_executableTester->expects($this->at(0))->method('__invoke')->with('/foo/bar')->will($this->returnValue(true));
        $this->_executableTester->expects($this->at(1))->method('__invoke')->with('/baz/bar')->will($this->returnValue(true));

        $locator = new Locator(array('/foo', '/baz'));
        $locator->setExecutableTester($this->_executableTester);

        $this->assertSame(array('/foo/bar', '/baz/bar'), $locator->locateAll('bar'));
    }

    /**
     * Verify that constructing from environment variables works
     *
     * @test
     * @covers ::__construct
     * @covers ::createFromPathEnvironmentVariable
     * @covers ::createFromEnvironment
     * @covers ::setExecutableTester
     */
    public function createFromEnvironment()
    {
        $env = $this->getMockBuilder('\Habitat\Environment\Environment')->disableOriginalConstructor()->setMethods(array('getenv'))->getMock();
        $env->expects($this->once())->method('getenv')->with('PATH')->will($this->returnValue('abcd'));

        $locator = Locator::createFromEnvironment($env);
    }
}
