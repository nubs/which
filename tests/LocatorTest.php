<?php
namespace Nubs\Which;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\Locator
 */
class LocatorTest extends PHPUnit_Framework_TestCase
{
    private $_pathHelper;

    private $_executableTester;

    private $_locator;

    public function setUp()
    {
        $this->_pathHelper = $this->getMockBuilder('\Nubs\Which\PathHelper')->disableOriginalConstructor()->setMethods(array('joinPaths', 'isAtom', 'isAbsolute'))->getMock();
        $this->_executableTester = $this->getMockBuilder('\Nubs\Which\ExecutableTester')->disableOriginalConstructor()->setMethods(array('__invoke'))->getMock();
        $this->_locator = new Locator(array('/foo'));
        $this->_locator->setPathHelper($this->_pathHelper);
        $this->_locator->setExecutableTester($this->_executableTester);
    }

    /**
     * Verify that a simple command works.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::setPathHelper
     * @covers ::pathHelper
     * @covers ::setExecutableTester
     * @covers ::executableTester
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     */
    public function locateSimpleCommand()
    {
        $this->_pathHelper->expects($this->once())->method('isAbsolute')->with('bar')->will($this->returnValue(false));
        $this->_pathHelper->expects($this->once())->method('isAtom')->with('bar')->will($this->returnValue(true));
        $this->_pathHelper->expects($this->once())->method('joinPaths')->with('/foo', 'bar')->will($this->returnValue('/foo/bar'));

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
     * @covers ::setPathHelper
     * @covers ::pathHelper
     * @covers ::setExecutableTester
     * @covers ::executableTester
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     */
    public function locateNonExecutableCommand()
    {
        $this->_pathHelper->expects($this->once())->method('isAbsolute')->with('bar')->will($this->returnValue(false));
        $this->_pathHelper->expects($this->once())->method('isAtom')->with('bar')->will($this->returnValue(true));
        $this->_pathHelper->expects($this->once())->method('joinPaths')->with('/foo', 'bar')->will($this->returnValue('/foo/bar'));

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
     * @covers ::setPathHelper
     * @covers ::pathHelper
     * @covers ::setExecutableTester
     * @covers ::executableTester
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     */
    public function locateAbsoluteCommand()
    {
        $this->_pathHelper->expects($this->once())->method('isAbsolute')->with('/foo/bar')->will($this->returnValue(true));

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
     * @covers ::setPathHelper
     * @covers ::pathHelper
     * @covers ::setExecutableTester
     * @covers ::executableTester
     * @covers ::_getPotentialCommandLocations
     */
    public function locateSubdirectoryCommand()
    {
        $this->_pathHelper->expects($this->once())->method('isAbsolute')->with('foo/bar')->will($this->returnValue(false));
        $this->_pathHelper->expects($this->once())->method('isAtom')->with('foo/bar')->will($this->returnValue(false));

        $this->assertNull($this->_locator->locate('foo/bar'));
    }

    /**
     * Verify that a command name that is empty returns null.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::setPathHelper
     * @covers ::pathHelper
     * @covers ::setExecutableTester
     * @covers ::executableTester
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     */
    public function locateEmptyCommand()
    {
        $this->_pathHelper->expects($this->once())->method('isAbsolute')->with('')->will($this->returnValue(false));
        $this->_pathHelper->expects($this->once())->method('isAtom')->with('')->will($this->returnValue(true));
        $this->_pathHelper->expects($this->once())->method('joinPaths')->with('/foo', '')->will($this->returnValue('/foo/'));

        $this->_executableTester->expects($this->once())->method('__invoke')->with('/foo/')->will($this->returnValue(false));

        $this->assertNull($this->_locator->locate(''));
    }

    /**
     * Verify that locate returns the first occurence of a command.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::setPathHelper
     * @covers ::pathHelper
     * @covers ::setExecutableTester
     * @covers ::executableTester
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     */
    public function locateMultipleLocations()
    {
        $this->_pathHelper->expects($this->once())->method('isAbsolute')->with('bar')->will($this->returnValue(false));
        $this->_pathHelper->expects($this->once())->method('isAtom')->with('bar')->will($this->returnValue(true));
        $this->_pathHelper->expects($this->at(2))->method('joinPaths')->with('/foo', 'bar')->will($this->returnValue('/foo/bar'));
        $this->_pathHelper->expects($this->at(3))->method('joinPaths')->with('/baz', 'bar')->will($this->returnValue('/baz/bar'));

        $this->_executableTester->expects($this->at(0))->method('__invoke')->with('/foo/bar')->will($this->returnValue(true));
        $this->_executableTester->expects($this->at(1))->method('__invoke')->with('/baz/bar')->will($this->returnValue(true));

        $locator = new Locator(array('/foo', '/baz'));
        $locator->setPathHelper($this->_pathHelper);
        $locator->setExecutableTester($this->_executableTester);

        $this->assertSame('/foo/bar', $locator->locate('bar'));
    }

    /**
     * Verify that locateAll returns all occurences of a command.
     *
     * @test
     * @covers ::__construct
     * @covers ::locateAll
     * @covers ::setPathHelper
     * @covers ::pathHelper
     * @covers ::setExecutableTester
     * @covers ::executableTester
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     */
    public function locateAll()
    {
        $this->_pathHelper->expects($this->once())->method('isAbsolute')->with('bar')->will($this->returnValue(false));
        $this->_pathHelper->expects($this->once())->method('isAtom')->with('bar')->will($this->returnValue(true));
        $this->_pathHelper->expects($this->at(2))->method('joinPaths')->with('/foo', 'bar')->will($this->returnValue('/foo/bar'));
        $this->_pathHelper->expects($this->at(3))->method('joinPaths')->with('/baz', 'bar')->will($this->returnValue('/baz/bar'));

        $this->_executableTester->expects($this->at(0))->method('__invoke')->with('/foo/bar')->will($this->returnValue(true));
        $this->_executableTester->expects($this->at(1))->method('__invoke')->with('/baz/bar')->will($this->returnValue(true));

        $locator = new Locator(array('/foo', '/baz'));
        $locator->setPathHelper($this->_pathHelper);
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
     * @covers ::setPathHelper
     * @covers ::setExecutableTester
     */
    public function createFromEnvironment()
    {
        $env = $this->getMockBuilder('\Habitat\Environment\Environment')->disableOriginalConstructor()->setMethods(array('getenv'))->getMock();
        $env->expects($this->once())->method('getenv')->with('PATH')->will($this->returnValue('abcd'));

        $locator = Locator::createFromEnvironment($env);
    }
}
