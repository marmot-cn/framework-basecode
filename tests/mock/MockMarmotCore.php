<?php
namespace Marmot\Basecode;

use Marmot\Basecode\MarmotCore;
use Marmot\Interfaces\Application\IApplication;
use Marmot\Interfaces\Application\IFramework;

class MockMarmotCore extends MarmotCore
{
    protected function initApplication() : void
    {
        $this->application = new MockApplication();
    }

    public function initApplicationPublic() : void
    {
        $this->initApplication();
    }

    protected function getApplication() : IApplication
    {
        return $this->application;
    }

    protected function initFramework() : void
    {
        $this->framework = new MockFramework();
    }

    public function initFrameworkPublic() : void
    {
        $this->initFramework();
    }

    protected function getFramework() : IFramework
    {
        return $this->framework;
    }

    public function initEnv()
    {
        parent::initEnv();
    }

    public function initError()
    {
        parent::initError();
    }

    public function getSdks() : array
    {
        return parent::getSdks();
    }

    protected function initDb()
    {
    }

    protected function initCache()
    {
    }

    protected function getAppPath() : string
    {
        return '';
    }

    protected function initAutoload()
    {
    }

    public function isPublicMockedErrorRoute()
    {
        return parent::isMockedErrorRoute();
    }
}
