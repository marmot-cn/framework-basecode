<?php
namespace Marmot\Basecode\Application;

interface IApplicationError
{
    public function initErrorConfig() : void;

    public function getErrorDescriptions() : array;
}
