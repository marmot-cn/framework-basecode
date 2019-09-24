<?php
namespace Marmot\Basecode\Adapter;

use Marmot\Basecode\Interfaces\IAsyncAdapter;
use Marmot\Basecode\Adapter\Restful\GuzzleAdapter;
use Marmot\Basecode\Classes\NullTranslator;
use Marmot\Basecode\Interfaces\IRestfulTranslator;

class MockAsyncAdapter extends GuzzleAdapter implements IAsyncAdapter
{
    public function fetchOneAsync(int $id)
    {
    }

    public function fetchListAsync(array $ids)
    {
    }

    public function searchAsync(
        array $filter = array(),
        array $sort = array(),
        int $number = 0,
        int $size = 20
    ) {
    }

    protected function getTranslator() : IRestfulTranslator
    {
        return new NullTranslator();
    }
}
