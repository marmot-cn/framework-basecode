<?php
namespace Marmot\Basecode\Classes;

use PHPUnit\Framework\TestCase;

use Marmot\Core;
use Marmot\Basecode\Classes\NullTranslator;
use Marmot\Interfaces\ICommand;

class NullTranslatorTest extends TestCase
{
    private $nullTranslator;

    public function setUp()
    {
        $this->nullTranslator = NullTranslator::getInstance();
    }

    public function testImplementsITranslator()
    {
        $this->assertInstanceOf('Marmot\Interfaces\ITranslator', $this->nullTranslator);
    }

    public function testImplementsNull()
    {
        $this->assertInstanceOf('Marmot\Interfaces\INull', $this->nullTranslator);
    }

    public function testArrayToObject()
    {
        $result = $this->nullTranslator->arrayToObject(array());

        $this->assertFalse($result);
        $this->assertEquals(TRANSLATOR_NOT_EXIST, Core::getLastError()->getId());
    }

    public function testObjectToArray()
    {
        $result = $this->nullTranslator->objectToArray('test');

        $this->assertFalse($result);
        $this->assertEquals(TRANSLATOR_NOT_EXIST, Core::getLastError()->getId());
    }
}
