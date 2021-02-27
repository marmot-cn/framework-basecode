<?php
namespace Marmot\Basecode\Classes;

use PHPUnit\Framework\TestCase;

use Marmot\Core;
use Marmot\Basecode\Classes\NullCommandHandler;
use Marmot\Interfaces\ICommand;

class NullCommandHandlerTest extends TestCase
{
    private $nullCommandHandler;

    public function setUp()
    {
        $this->nullCommandHandler = NullCommandHandler::getInstance();
    }

    public function testImplementsICommandHandler()
    {
        $this->assertInstanceOf('Marmot\Interfaces\ICommandHandler', $this->nullCommandHandler);
    }

    public function testImplementsNull()
    {
        $this->assertInstanceOf('Marmot\Interfaces\INull', $this->nullCommandHandler);
    }

    public function testExecute()
    {
        $command = $this->getMockBuilder(ICommand::class)
                        ->getMock();
        
        $result = $this->nullCommandHandler->execute($command);
        $this->assertFalse($result);
        $this->assertEquals(COMMAND_HANDLER_NOT_EXIST, Core::getLastError()->getId());
    }
}
