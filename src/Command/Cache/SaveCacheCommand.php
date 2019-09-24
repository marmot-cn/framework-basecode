<?php
namespace Marmot\Basecode\Command\Cache;

use Marmot\Basecode\Interfaces\Command;
use Marmot\Basecode\Observer\CacheObserver;
use Marmot\Basecode\Classes\Transaction;
use Marmot\Core;

/**
 * 添加cache缓存命令
 * @author chloroplast1983
 */

class SaveCacheCommand implements Command
{
    private $key;

    private $data;

    private $time;

    private $cacheDriver;
    
    public function __construct($key, $data, $time = 0)
    {
        $this->key = $key;
        $this->data = $data;
        $this->time = $time;
        $this->cacheDriver = Core::$cacheDriver;
    }

    public function __destruct()
    {
        unset($this->key);
        unset($this->data);
        unset($this->time);
        unset($this->cacheDriver);
    }

    protected function getKey() : string
    {
        return $this->key;
    }

    protected function getData()
    {
        return $this->data;
    }

    protected function getTime() : int
    {
        return $this->time;
    }

    protected function getCacheDriver()
    {
        return $this->cacheDriver;
    }

    public function execute() : bool
    {
        return $this->getCacheDriver()->save($this->key, $this->data, $this->time);
    }

    public function undo() : bool
    {
        return $this->getCacheDriver()->delete($this->key);
    }
}
