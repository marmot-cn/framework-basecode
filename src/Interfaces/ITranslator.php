<?php
namespace Marmot\Basecode\Interfaces;

/**
 * 翻译器接口
 * @codeCoverageIgnore
 */
interface ITranslator
{
    public function arrayToObject(array $expression, $object = null);

    public function objectToArray($object, array $keys = array());
}
