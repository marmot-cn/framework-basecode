<?php
namespace Marmot\Basecode\Interfaces;

use Marmot\Basecode\Classes\Request;

interface IMediaTypeStrategy
{
    public function validate(Request $request) : bool;

    public function decode($rawData);
}
