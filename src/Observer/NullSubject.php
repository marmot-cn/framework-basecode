<?php
namespace Marmot\Basecode\Observer;

use Marmot\Basecode\Interfaces\Subject as ISubject;
use Marmot\Basecode\Interfaces\Observer;
use Marmot\Basecode\Interfaces\INull;
use Marmot\Core;

class NullSubject implements ISubject, INull
{
    private static $instance;
    
    private function __constructor()
    {
    }

    public static function &getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
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
