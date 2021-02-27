<?php
namespace Marmot\Basecode\Controller;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class IndexControllerTest extends TestCase
{
    private $controller;

    public function setUp()
    {
        $this->controller = new IndexController();
    }

    public function tearDown()
    {
        unset($this->controller);
    }

    public function testExtendsController()
    {
        $this->assertInstanceOf(
            'Marmot\Basecode\Classes\Controller',
            $this->controller
        );
    }
}
