<?php
namespace Marmot\Basecode\Command\Cache;

use Marmot\Basecode\Interfaces\Command;
use Marmot\Basecode\Observer;
use Marmot\Basecode\Classes;
use Marmot\Core;

/**
 * 删除cache缓存命令
 * @author chloroplast
 */

class DelCacheCommand implements Command
{
    private $key;

    private $cacheDriver;
    
    public function __construct(string $key)
    {
        $this->key = $key;
        $this->cacheDriver = Core::$cacheDriver;
    }

    protected function getKey() : string
    {
        return $this->key;
    }

    protected function getCacheDriver()
    {
        return $this->cacheDriver;
    }

    public function execute() : bool
    {
        return $this->getCacheDriver()->delete($this->key);
    }

    /**
     * @codeCoverageIgnore
     */
    public function undo()
    {
        //
    }
}
