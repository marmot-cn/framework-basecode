<?php
//powered by chloroplast
namespace Marmot\Basecode\Classes;

class MockRepository extends Repository
{
    protected function getActualAdapter()
    {
    }

    protected function getMockAdapter()
    {
    }

    public function getAdapter()
    {
        return parent::getAdapter();
    }

    public function isMocked() : bool
    {
        return parent::isMocked();
    }
}
