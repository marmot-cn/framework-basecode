<?php
namespace Marmot\Basecode\Adapter\Restful\Translator;

use Marmot\Basecode\Interfaces\ITranslator;
use Marmot\Basecode\Adapter\Restful\CacheResponse;
use Marmot\Basecode\Adapter\Restful\NullResponse;
use GuzzleHttp\Psr7\Response;

class CacheResponseTranslator implements ITranslator
{
    public function arrayToObject(array $expression, $cacheResponse = null)
    {
        unset($cacheResponse);

        if (!isset($expression['statusCode']) ||
            !isset($expression['contents']) ||
            !isset($expression['responseHeaders'])) {
            return new NullResponse();
        }
        return new CacheResponse(
            $expression['statusCode'],
            $expression['contents'],
            $expression['responseHeaders'],
            isset($expression['ttl']) ? $expression['ttl'] : 0
        );
    }

    public function objectToArray($cacheResponse, array $keys = array())
    {
        unset($keys);
        return array(
            'statusCode' => $cacheResponse->getStatusCode(),
            'contents' => $cacheResponse->getBody()->getContents(),
            'responseHeaders' => $cacheResponse->getHeaders(),
            'ttl' => $cacheResponse->getTTL()
        );
    }
}
