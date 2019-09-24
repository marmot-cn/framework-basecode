<?php
namespace Marmot\Basecode\Interfaces;

interface IRestfulTranslator extends ITranslator
{
    public function arrayToObjects(array $expression) : array;
}
