<?php
namespace Marmot\Basecode\Observer;

use Marmot\Basecode\Interfaces\Command;

class MockCacheObserver extends CacheObserver
{
    public function getCacheCommand() : Command
    {
        return parent::getCacheCommand();
    }
}
