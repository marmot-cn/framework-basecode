<?php
namespace Marmot\Basecode\Application;

interface IApplicationRoute
{
    public function getIndexRoute() : array;

    public function getRouteRules() : array;
}
