<?php
namespace Marmot\Basecode\Observer;

use PHPUnit\Framework\TestCase;

use Marmot\Core;

class NullObserverTest extends TestCase
{
    private $observer;

    public function setUp()
    {
        $this->observer = NullObserver::getInstance();
    }

    public function tearDown()
    {
        unset($this->observer);
    }

    public function testImplementsObserver()
    {
        $this->assertInstanceOf('Marmot\Basecode\Interfaces\Observer', $this->observer);
    }

    public function testImplementsNull()
    {
        $this->assertInstanceOf('Marmot\Basecode\Interfaces\INull', $this->observer);
    }

    public function testUpdate()
    {
        $result = $this->observer->update();
        $this->assertFalse($result);
        $this->assertEquals(OBSERVER_NOT_EXIST, Core::getLastError()->getId());
    }
}
