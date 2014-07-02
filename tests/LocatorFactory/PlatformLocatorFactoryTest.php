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
        $this->_isolator = $this->getMockBuilder('\Icecave\Isolator\Isolator')->disableOriginalConstructor()->setMethods(array('defined'))->getMock();
    }

    /**
     * Verify that constructing from environment variables works on windows.
     *
     * @test
     * @covers ::__construct
     * @covers ::createFromPath
     * @covers ::createFromEnvironment
     * @uses \Nubs\Which\Locator::__construct
     * @uses \Nubs\Which\Locator::setPathHelper
     * @uses \Nubs\Which\LocatorFactory\WindowsLocatorFactory::createFromPath
     */
    public function createFromEnvironmentWithWindows()
    {
        $this->_isolator->expects($this->once())->method('defined')->with('PHP_WINDOWS_VERSION_BUILD')->will($this->returnValue(true));

        $env = $this->getMockBuilder('\Habitat\Environment\Environment')->disableOriginalConstructor()->setMethods(array('getenv'))->getMock();
        $env->expects($this->once())->method('getenv')->with('PATH')->will($this->returnValue('abcd'));

        $locatorFactory = new PlatformLocatorFactory($this->_isolator);
        $locator = $locatorFactory->createFromEnvironment($env);
    }

    /**
     * Verify that constructing from environment variables works on linux.
     *
     * @test
     * @covers ::__construct
     * @covers ::createFromPath
     * @covers ::createFromEnvironment
     * @uses \Nubs\Which\Locator::__construct
     * @uses \Nubs\Which\Locator::setPathHelper
     * @uses \Nubs\Which\LocatorFactory\PosixLocatorFactory::createFromPath
     */
    public function createFromEnvironmentWithPosix()
    {
        $this->_isolator->expects($this->once())->method('defined')->with('PHP_WINDOWS_VERSION_BUILD')->will($this->returnValue(false));

        $env = $this->getMockBuilder('\Habitat\Environment\Environment')->disableOriginalConstructor()->setMethods(array('getenv'))->getMock();
        $env->expects($this->once())->method('getenv')->with('PATH')->will($this->returnValue('abcd'));

        $locatorFactory = new PlatformLocatorFactory($this->_isolator);
        $locator = $locatorFactory->createFromEnvironment($env);
    }
}
