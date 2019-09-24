<?php
namespace Marmot\Basecode\Controller;

use Marmot\Basecode\Classes\Controller;

class IndexController extends Controller
{
    /**
     * @codeCoverageIgnore
     */
    public function index()
    {
        var_dump("hello marmot");
        exit();
    }
}
