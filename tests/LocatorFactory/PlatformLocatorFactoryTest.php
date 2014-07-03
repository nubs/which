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
     * @covers ::createFromEnvironment
     * @uses \Nubs\Which\Locator::__construct
     * @uses \Nubs\Which\LocatorFactory\WindowsLocatorFactory::createFromEnvironment
     * @uses \Nubs\Which\LocatorFactory\WindowsLocatorFactory::createFromPath
     * @uses \Nubs\Which\PathBuilder\WindowsPathBuilder::__construct
     * @uses \Nubs\Which\ExecutableTester::__construct
     */
    public function createFromEnvironmentWithWindows()
    {
        $this->_isolator->expects($this->once())->method('defined')->with('PHP_WINDOWS_VERSION_BUILD')->will($this->returnValue(true));

        $env = $this->getMockBuilder('\Habitat\Environment\Environment')->disableOriginalConstructor()->setMethods(['getenv'])->getMock();
        $env->expects($this->at(0))->method('getenv')->with('PATH')->will($this->returnValue('abcd'));
        $env->expects($this->at(1))->method('getenv')->with('PATHEXT')->will($this->returnValue('abcd'));

        $locatorFactory = new PlatformLocatorFactory($this->_isolator);
        $locator = $locatorFactory->createFromEnvironment($env);
    }

    /**
     * Verify that constructing from environment variables works on linux.
     *
     * @test
     * @covers ::__construct
     * @covers ::createFromEnvironment
     * @uses \Nubs\Which\Locator::__construct
     * @uses \Nubs\Which\LocatorFactory\PosixLocatorFactory::createFromEnvironment
     * @uses \Nubs\Which\LocatorFactory\PosixLocatorFactory::createFromPath
     * @uses \Nubs\Which\PathBuilder\PosixPathBuilder::__construct
     * @uses \Nubs\Which\ExecutableTester::__construct
     */
    public function createFromEnvironmentWithPosix()
    {
        $this->_isolator->expects($this->once())->method('defined')->with('PHP_WINDOWS_VERSION_BUILD')->will($this->returnValue(false));

        $env = $this->getMockBuilder('\Habitat\Environment\Environment')->disableOriginalConstructor()->setMethods(['getenv'])->getMock();
        $env->expects($this->once())->method('getenv')->with('PATH')->will($this->returnValue('abcd'));

        $locatorFactory = new PlatformLocatorFactory($this->_isolator);
        $locator = $locatorFactory->createFromEnvironment($env);
    }
}
