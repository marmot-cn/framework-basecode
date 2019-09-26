<?php
namespace Marmot\Basecode\View;

use PHPUnit\Framework\TestCase;
use Marmot\Core;

class EmptyViewTest extends TestCase
{

    private $stub;

    public function setUp()
    {
        $this->stub = new EmptyView();
    }

    public function tearDown()
    {
        unset($this->stub);
    }

    public function testCorrectImplementIView()
    {
        $this->assertInstanceof('Marmot\Interfaces\IView', $this->stub);
    }

    public function testDisplay()
    {
        $this->assertEmpty($this->stub->display());
    }
}
