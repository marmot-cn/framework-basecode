<?php
namespace Marmot\Basecode\Classes;

class Server
{
    public static function get(string $name = '', $value = '')
    {
        if (empty($name)) {
            return $_SERVER;
        }

        return isset($_SERVER[$name]) ? $_SERVER[$name] : $value;
    }
}
