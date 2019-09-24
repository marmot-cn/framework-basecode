<?php
namespace Marmot\Basecode\Classes;

use Marmot\Basecode\Interfaces\ICommandHandler;
use Marmot\Basecode\Interfaces\ICommand;
use Marmot\Basecode\Interfaces\INull;

use Marmot\Core;

class NullCommandHandler implements ICommandHandler, INull
{
    private static $instance;
    
    private function __construct()
    {
    }

    public static function &getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function commandHandlerNotExist() : bool
    {
        Core::setLastError(COMMAND_HANDLER_NOT_EXIST);
        return false;
    }

    public function execute(ICommand $command)
    {
        return $this->commandHandlerNotExist();
    }
}
