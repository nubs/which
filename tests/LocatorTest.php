<?php
namespace Nubs\Which;

use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \Nubs\Which\Locator
 */
class LocatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that a simple command works.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateSimpleCommand()
    {
        $vfs = vfsStream::setup('foo');
        $vfs->addChild(vfsStream::newFile('bar', 0777));

        $locator = new Locator(array($vfs->url()));

        $this->assertSame('vfs://foo/bar', $locator->locate('bar'));
    }

    /**
     * Verify that a non-executable path does not get returned.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateNonExecutableCommand()
    {
        $vfs = vfsStream::setup('foo');
        $vfs->addChild(vfsStream::newFile('bar', 0666));

        $locator = new Locator(array($vfs->url()));

        $this->assertNull($locator->locate('bar'));
    }

    /**
     * Verify that an absolute path to a command works.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateAbsoluteCommand()
    {
        $locator = new Locator(array());

        $this->assertSame('/usr/bin/env', $locator->locate('/usr/bin/env'));
    }

    /**
     * Verify that a command name that is in a subdirectory returns no result.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateSubdirectoryCommand()
    {
        $locator = new Locator(array());

        $this->assertSame(null, $locator->locate('foo/bar'));
    }

    /**
     * Verify that locate returns the first occurence of a command.
     *
     * @test
     * @covers ::__construct
     * @covers ::locate
     * @covers ::locateAll
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateMultipleLocations()
    {
        $foo = vfsStream::newDirectory('foo', 0777);
        $foo->addChild(vfsStream::newFile('bar', 0777));

        $baz = vfsStream::newDirectory('baz', 0777);
        $baz->addChild(vfsStream::newFile('bar', 0777));

        $vfs = vfsStream::setup('base');
        $vfs->addChild($foo);
        $vfs->addChild($baz);

        $locator = new Locator(array($foo->url(), $baz->url()));

        $this->assertSame('vfs://base/foo/bar', $locator->locate('bar'));
    }

    /**
     * Verify that locateAll returns all occurences of a command.
     *
     * @test
     * @covers ::__construct
     * @covers ::locateAll
     * @covers ::_getPotentialCommandLocations
     * @covers ::_getPaths
     * @covers ::_isValidCommandName
     * @covers ::_isAbsoluteCommandPath
     */
    public function locateAll()
    {
        $foo = vfsStream::newDirectory('foo', 0777);
        $foo->addChild(vfsStream::newFile('bar', 0777));

        $baz = vfsStream::newDirectory('baz', 0777);
        $baz->addChild(vfsStream::newFile('bar', 0777));

        $vfs = vfsStream::setup('base');
        $vfs->addChild($foo);
        $vfs->addChild($baz);

        $locator = new Locator(array($foo->url(), $baz->url()));

        $this->assertSame(array('vfs://base/foo/bar', 'vfs://base/baz/bar'), $locator->locateAll('bar'));
    }

    /**
     * Verify that constructing from environment variables works
     *
     * @test
     * @covers ::__construct
     * @covers ::createFromPathEnvironmentVariable
     * @covers ::createFromEnvironment
     */
    public function createFromEnvironment()
    {
        $env = $this->getMockBuilder('\Habitat\Environment\Environment')->disableOriginalConstructor()->setMethods(array('getenv'))->getMock();
        $env->expects($this->once())->method('getenv')->with('PATH')->will($this->returnValue('abcd'));

        $locator = Locator::createFromEnvironment($env);
    }
}
