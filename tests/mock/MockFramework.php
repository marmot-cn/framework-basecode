<?php
namespace Marmot\Basecode;

use Marmot\Basecode\Application\IFramework;

class MockFramework implements IFramework
{
    public function initErrorConfig() : void
    {
    }

    public function getErrorDescriptions() : array
    {
        return [];
    }

    public function initConfig() : void
    {
    }
}
