<?php
/**
 * core 核心文件
 *
 * @author  chloroplast1983
 * @version 1.0.20131007
 */

namespace Marmot\Basecode;

use Marmot\Basecode\Classes\Error;
use Marmot\Basecode\Classes\Server;
use Marmot\Interfaces\Application\IApplication;
use Marmot\Interfaces\Application\IFramework;

/**
 * 文件核心类
 *
 * @author  chloroplast1983
 * @version 1.0.20130916
 */
abstract class MarmotCore
{
    //框架内的容器,这里暂时使用的是第三方的PHP-DI容器
    public static $container;

    //缓存驱动
    public static $cacheDriver;

    //数据库驱动
    public static $dbDriver;

    //mongo驱动
    public static $mongoDriver;

    //上一次错误
    private static $lastError;

    protected static $errorDescriptions;

    protected $application;

    protected $framework;
    
    /**
     * 网站正常启动流程
     */
    public function init()
    {
        $this->initAutoload();
        $this->initFramework();
        $this->initApplication();
        $this->initContainer();//引入容器
        $this->initEnv();//初始化环境
        $this->initCache();//初始化缓存使用
        $this->initDb();//初始化mysql
        $this->initError();
        $this->initRoute();
    }

    /**
     * cli模式专用启动路程,用于引导操作框架的一些操作.
     * 在这里我们要实现如下功能:
     * 1. 自动加载
     * 2. 初始化容器
     * 3. 初始化缓存
     * 4. 初始化测试持久层存储
     */
    public function initCli()
    {
        $this->initAutoload();//autoload
        $this->initFramework();
        $this->initApplication();
        $this->initContainer();//引入容器
        $this->initEnv();//初始化环境
        $this->initCache();//初始化缓存使用
        $this->initDb();//初始化mysql
        $this->initError();
    }
    abstract protected function initFramework() : void;

    abstract protected function getFramework() : IFramework;
    
    abstract protected function initApplication() : void;

    abstract protected function getApplication() : IApplication;
    
    protected function getSdks() : array
    {
        return [];
    }
    
    /**
     * 符合PSR4自动加载规范
     *
     * 1. 加载第三方的autoload,主要是composer管理的第三方依赖
     * 2. 子类继承覆写自己的加载方式
     */
    abstract protected function initAutoload();

    /**
     * 初始化网站运行环境的一些全局变量
     *
     * @author  chloroplast
     * @version 1.0.20131016
     */
    protected function initEnv()
    {
        self::$container->set('time', time());
        $this->getApplication()->initConfig();
        $this->getFramework()->initConfig();
    }

    /**
     * 初始化错误信息
     */
    protected function initError()
    {
        include_once 'errorConfig.php';
        self::$errorDescriptions = include_once 'errorDescriptionConfig.php';

        $this->initFrameworkError();
        $this->initApplicationError();
        $this->initSdksError();

        self::setLastError(ERROR_NOT_DEFINED);
    }

    private function initFrameworkError()
    {
        $framework = $this->getFramework();
        $framework->initErrorConfig();

        self::$errorDescriptions = self::$errorDescriptions + $framework->getErrorDescriptions();
    }

    private function initApplicationError()
    {
        $application = $this->getApplication();
        $application->initErrorConfig();

        self::$errorDescriptions = self::$errorDescriptions + $application->getErrorDescriptions();
    }

    private function initSdksError()
    {
        $sdks = $this->getSdks();
        if (!empty($sdks)) {
            foreach ($sdks as $sdk) {
                $sdk->initErrorConfig();
                self::$errorDescriptions = self::$errorDescriptions + $sdk->getErrorDescriptions();
            }
        }
    }

    public static function setLastError(int $errorCode = 0, array $source = array())
    {

        if (!isset(self::$errorDescriptions[$errorCode])) {
            return false;
        }

        self::$lastError  = new Error(
            $errorCode,
            self::$errorDescriptions[$errorCode]['link'],
            self::$errorDescriptions[$errorCode]['status'],
            self::$errorDescriptions[$errorCode]['code'],
            self::$errorDescriptions[$errorCode]['title'],
            self::$errorDescriptions[$errorCode]['detail'],
            !empty($source) ? $source : self::$errorDescriptions[$errorCode]['source'],
            self::$errorDescriptions[$errorCode]['meta']
        );
    }

    public static function getLastError() : Error
    {
        return self::$lastError ;
    }

    /**
     * 创建容器
     *
     * @author  chloroplast1983
     * @version 1.0.20160215
     */
    protected function initContainer()
    {
        //初始化容器
        $containerBuilder = new \DI\ContainerBuilder();
        //这里我们需要使用annotation,所以开启了此功能
        $containerBuilder->useAnnotations(true);
        //为容器设置缓存
        $containerCache = new \Doctrine\Common\Cache\ArrayCache();
        $containerCache->setNamespace('phpcore');
        $containerBuilder->setDefinitionCache($containerCache);

        $containerBuilder->writeProxiesToFile(true, $this->getAppPath().'cache/proxies');
        //为容器设置配置文件
        $containerBuilder->addDefinitions($this->getAppPath().'config.'.$_ENV['APP_ENV'].'.php');
        //创建容器
        self::$container = $containerBuilder->build();
    }

    abstract protected function getAppPath() : string;
    
    /**
     * 路由,需要解决以前随意由个人设置路由的习惯,
     * 而希望能用统一的路由风格来解决这个问题.
     *
     * @version 1.0.20160204
     */
    protected function initRoute()
    {
        //创建路由规则,如果对外提供接口考虑token用于验证
        $dispatcher = \FastRoute\cachedDispatcher(
            function (\FastRoute\RouteCollector $each) {
                //添加默认首页路由 -- 开始
                list($method, $rule, $controller) = $this->getApplication()->getIndexRoute();
                $each->addRoute($method, $rule, $controller);
                
                //获取配置好的路由规则
                $routeRules = $this->getApplication()->getRouteRules();
                foreach ($routeRules as $route) {
                    $each->addRoute($route['method'], $route['rule'], $route['controller']);
                }
            },
            [
                'cacheFile' => $this->getAppPath(). 'cache/route.cache',
                'cacheDisabled' => self::$container->get('cache.route.disable'),
            ]
        );

        $httpMethod = Server::get('REQUEST_METHOD');
        $uri = rawurldecode(parse_url(Server::get('REQUEST_URI'), PHP_URL_PATH));
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        $controller = ['Marmot\Basecode\Controller\IndexController','index'];
        $parameters = [];

        $routeResult = $routeInfo[0];

        if ($routeResult == \FastRoute\Dispatcher::NOT_FOUND) {
            self::setLastError(ROUTE_NOT_EXIST);
        } elseif ($routeResult == \FastRoute\Dispatcher::METHOD_NOT_ALLOWED) {
            self::setLastError(METHOD_NOT_ALLOWED);
        } elseif ($routeResult == \FastRoute\Dispatcher::FOUND) {
            if ($this->isMockedErrorRoute()) {
                self::setLastError(Server::get('HTTP_MOCK_ERROR', 0));
            } else {
                $controller = $routeInfo[1];
                $parameters = $routeInfo[2];
            }
        }

        self::$container->call($controller, $parameters);
    }

    protected function isMockedErrorRoute()
    {
        $mockStatus = Server::get('HTTP_MOCK_STATUS', 0);
        $mockError = Server::get('HTTP_MOCK_ERROR', 0);

        return $mockStatus && $mockError;
    }

    abstract protected function initDb();
    
    abstract protected function initCache();
}
