<?php
namespace Marmot\Basecode\Command;

use Marmot\Basecode\Interfaces\Command;

class MockCommand implements Command
{
    public function execute()
    {
        return true;
    }

    public function undo()
    {
        return true;
    }
}
