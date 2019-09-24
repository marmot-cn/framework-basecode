<?php
//powered by chloroplast
namespace Marmot\Basecode\Classes;

abstract class Repository
{
    abstract protected function getActualAdapter();

    abstract protected function getMockAdapter();

    protected function getAdapter()
    {
        return $this->isMocked()? $this->getMockAdapter() : $this->getActualAdapter();
    }

    protected function isMocked() : bool
    {
        $mockStatus = Server::get('HTTP_MOCK_STATUS', 0);
        return $mockStatus > 0;
    }
}
