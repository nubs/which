<?php
namespace Nubs\Which\LocatorFactory;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Nubs\Which\LocatorFactory\WindowsLocatorFactory
 */
class WindowsLocatorFactoryTest extends PHPUnit_Framework_TestCase
{
    private $_locatorFactory;

    public function setUp()
    {
        $this->_locatorFactory = new WindowsLocatorFactory();
    }

    /**
     * Verify that constructing from environment variables works.
     *
     * @test
     * @covers ::createFromPath
     * @covers ::create
     * @uses \Nubs\Which\Locator::__construct
     * @uses \Nubs\Which\PathBuilder\WindowsPathBuilder::__construct
     * @uses \Nubs\Which\ExecutableTester::__construct
     */
    public function create()
    {
        $env = $this->createMock('\Habitat\Environment\Environment');
        $env->expects($this->at(0))->method('getenv')->with('PATH')->will($this->returnValue('abcd'));
        $env->expects($this->at(1))->method('getenv')->with('PATHEXT')->will($this->returnValue('abcd'));

        $locator = $this->_locatorFactory->create($env);
    }
}
