<?php
namespace Marmot\Basecode\Classes;

use PHPUnit\Framework\TestCase;

use Marmot\Core;
use Marmot\Basecode\Classes\Request;

class ControllerTest extends TestCase
{
    private $stub;

    public function setUp()
    {
        $this->stub = $this->getMockBuilder('Marmot\Basecode\Classes\Controller')
                      ->getMockForAbstractClass();
    }

    public function tearDown()
    {
        unset($this->stub);
    }

    /**
     * 期望 request 和 reponse 正确被赋值对象
     */
    public function testConstruct()
    {
        $this->assertInstanceof(
            'Marmot\Basecode\Classes\Request',
            $this->stub->getRequest()
        );
        $this->assertInstanceof(
            'Marmot\Basecode\Classes\Response',
            $this->stub->getResponse()
        );
    }

    /**
     * 因为response默认是jsonApi格式,所有数据都会被json_encode
     * 所以我们期望渲染一个数组,并且期望一个json_encode格式的数组
     */
    public function testRender()
    {
        //mock interface
        $ivew = $this->getMockBuilder('Marmot\Interfaces\IView')
                     ->setMethods(['display'])
                     ->getMock();

        $ivew->expects($this->any())
             ->method('display')
             ->will($this->returnValue(json_encode(array('key'=>'value'))));

        $this->stub->render($ivew);
        $this->expectOutputString(json_encode(array('key'=>'value')));
    }
}
