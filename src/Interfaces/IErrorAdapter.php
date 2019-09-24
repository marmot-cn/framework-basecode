<?php
namespace Marmot\Basecode\Interfaces;

interface IErrorAdapter
{
    public function lastErrorId() : int;

    public function lastErrorInfo() : array;
}
