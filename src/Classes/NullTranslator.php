<?php
namespace Marmot\Basecode\Classes;

use Marmot\Interfaces\ITranslator;
use Marmot\Interfaces\INull;
use Marmot\Core;

class NullTranslator implements ITranslator, INull
{
    private static $instance;
    
    private function __construct()
    {
    }

    public static function &getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function translatorNotExist() : bool
    {
        Core::setLastError(TRANSLATOR_NOT_EXIST);
        return false;
    }

    public function arrayToObject(array $expression, $object = null)
    {
        unset($expression);
        unset($object);

        return $this->translatorNotExist();
    }

    public function objectToArray($object, array $keys = array())
    {
        unset($object);
        unset($keys);

        return $this->translatorNotExist();
    }
}
