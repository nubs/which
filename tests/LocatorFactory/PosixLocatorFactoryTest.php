<?php
namespace Nubs\Which\LocatorFactory;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\LocatorFactory\PosixLocatorFactory
 */
class PosixLocatorFactoryTest extends PHPUnit_Framework_TestCase
{
    private $_locatorFactory;

    public function setUp()
    {
        $this->_locatorFactory = new PosixLocatorFactory();
    }

    /**
     * Verify that constructing from environment variables works.
     *
     * @test
     * @covers ::createFromPath
     * @covers ::createFromEnvironment
     * @uses \Nubs\Which\Locator::__construct
     */
    public function createFromEnvironment()
    {
        $env = $this->getMockBuilder('\Habitat\Environment\Environment')->disableOriginalConstructor()->setMethods(array('getenv'))->getMock();
        $env->expects($this->once())->method('getenv')->with('PATH')->will($this->returnValue('abcd'));

        $locator = $this->_locatorFactory->createFromEnvironment($env);
    }
}
