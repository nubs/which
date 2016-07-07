<?php
namespace Nubs\Which\LocatorFactory;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\LocatorFactory\PlatformLocatorFactory
 */
class PlatformLocatorFactoryTest extends PHPUnit_Framework_TestCase
{
    private $_isolator;

    public function setUp()
    {
        $this->_isolator = $this->getMockBuilder('\Icecave\Isolator\Isolator')->disableOriginalConstructor()->setMethods(['defined'])->getMock();
    }

    /**
     * Verify that constructing from environment variables works on windows.
     *
     * @test
     * @covers ::__construct
     * @covers ::create
     * @uses \Nubs\Which\Locator::__construct
     * @uses \Nubs\Which\LocatorFactory\WindowsLocatorFactory::create
     * @uses \Nubs\Which\LocatorFactory\WindowsLocatorFactory::createFromPath
     * @uses \Nubs\Which\PathBuilder\WindowsPathBuilder::__construct
     * @uses \Nubs\Which\ExecutableTester::__construct
     */
    public function createWithWindows()
    {
        $this->_isolator->expects($this->once())->method('defined')->with('PHP_WINDOWS_VERSION_BUILD')->will($this->returnValue(true));

        $env = $this->createMock('\Habitat\Environment\Environment');
        $env->expects($this->at(0))->method('getenv')->with('PATH')->will($this->returnValue('abcd'));
        $env->expects($this->at(1))->method('getenv')->with('PATHEXT')->will($this->returnValue('abcd'));

        $locatorFactory = new PlatformLocatorFactory($this->_isolator);
        $locator = $locatorFactory->create($env);
    }

    /**
     * Verify that constructing from environment variables works on linux.
     *
     * @test
     * @covers ::__construct
     * @covers ::create
     * @uses \Nubs\Which\Locator::__construct
     * @uses \Nubs\Which\LocatorFactory\PosixLocatorFactory::create
     * @uses \Nubs\Which\LocatorFactory\PosixLocatorFactory::createFromPath
     * @uses \Nubs\Which\PathBuilder\PosixPathBuilder::__construct
     * @uses \Nubs\Which\ExecutableTester::__construct
     */
    public function createWithPosix()
    {
        $this->_isolator->expects($this->once())->method('defined')->with('PHP_WINDOWS_VERSION_BUILD')->will($this->returnValue(false));

        $env = $this->createMock('\Habitat\Environment\Environment');
        $env->expects($this->once())->method('getenv')->with('PATH')->will($this->returnValue('abcd'));

        $locatorFactory = new PlatformLocatorFactory($this->_isolator);
        $locator = $locatorFactory->create($env);
    }
}
