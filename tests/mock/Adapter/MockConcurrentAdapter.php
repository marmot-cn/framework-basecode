<?php
namespace Marmot\Basecode\Adapter;

class MockConcurrentAdapter extends ConcurrentAdapter
{
    public function getGuzzleConcurrentAdapter()
    {
        return parent::getGuzzleConcurrentAdapter();
    }
}
