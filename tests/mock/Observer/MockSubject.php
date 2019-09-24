<?php
namespace Marmot\Basecode\Observer;

class MockSubject extends Subject
{
    public function getObservers() : array
    {
        return parent::getObservers();
    }
}
