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
     * @covers ::create
     * @uses \Nubs\Which\Locator::__construct
     * @uses \Nubs\Which\PathBuilder\PosixPathBuilder::__construct
     * @uses \Nubs\Which\ExecutableTester::__construct
     */
    public function create()
    {
        $env = $this->getMockBuilder('\Habitat\Environment\Environment')->disableOriginalConstructor()->setMethods(['getenv'])->getMock();
        $env->expects($this->once())->method('getenv')->with('PATH')->will($this->returnValue('abcd'));

        $locator = $this->_locatorFactory->create($env);
    }
}
