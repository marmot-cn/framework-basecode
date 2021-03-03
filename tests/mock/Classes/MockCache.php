<?php
//powered by chloroplast
namespace Marmot\Basecode\Classes;

class MockCache extends Cache
{
    public function getKey() : string
    {
        return parent::getKey();
    }

    public function formatID($id) : string
    {
        return parent::formatID($id);
    }

    public function getCacheDriver()
    {
        return parent::getCacheDriver();
    }
}
