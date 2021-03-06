<?php
namespace Marmot\Basecode\Observer;

use Marmot\Interfaces\Subject as ISubject;
use Marmot\Interfaces\Observer;
use Marmot\Interfaces\INull;
use Marmot\Core;

class NullSubject implements ISubject, INull
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

    public function attach(Observer $observer)
    {
        unset($observer);
        return $this->subjectNotExist();
    }

    public function detach(Observer $observer)
    {
        unset($observer);
        return $this->subjectNotExist();
    }

    public function notifyObserver()
    {
        return $this->subjectNotExist();
    }

    private function subjectNotExist() : bool
    {
        Core::setLastError(SUBJECT_NOT_EXIST);
        return false;
    }
}
