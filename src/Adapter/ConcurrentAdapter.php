<?php
namespace Marmot\Basecode\Adapter;

use Marmot\Basecode\Adapter\Restful\GuzzleConcurrentAdapter;
use Marmot\Basecode\Adapter\Restful\GuzzleAdapter;
use Marmot\Basecode\Interfaces\IRepository;
use Marmot\Basecode\Interfaces\IAsyncAdapter;

abstract class ConcurrentAdapter
{
    private $guzzleConcurrentAdapter;

    public function __construct()
    {
        $this->guzzleConcurrentAdapter = new GuzzleConcurrentAdapter();
    }

    protected function getGuzzleConcurrentAdapter()
    {
        return $this->guzzleConcurrentAdapter;
    }

    public function addPromise($key, $asyncRequest, IAsyncAdapter $adapter)
    {
        if ($adapter instanceof GuzzleAdapter) {
            $this->getGuzzleConcurrentAdapter()->addPromise(
                $key,
                $asyncRequest,
                $adapter
            );
        }
    }

    public function run()
    {
        $guzzleResult = $this->getGuzzleConcurrentAdapter()->run();

        return $guzzleResult;
    }
}
