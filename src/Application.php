<?php
class Application
{
    private $_controllerPath = '../controllers';
    private $_bootstrap;
    private $_eventManager;
    private $_page = '';
    private $_headers = array();

    public function __construct(Bootstrap $bootstrap = null)
    {
        if (!$bootstrap) {
            $this->_bootstrap = ($this->_bootstrap) ? $this->_bootstrap : new Bootstrap();
        } else {
            $this->_bootstrap = $bootstrap;
        }
    }

    public function setControllerPath($path)
    {
        $this->_controllerPath = $path;
    }

    public function getControllerPath()
    {
        return $this->_controllerPath;
    }

    public function setEventManager(EventManager $manager)
    {
        $this->_eventManager = $manager;
    }

    public function getEventManager()
    {
        if (!$this->_eventManager) {
            $this->_eventManager = new EventManager();
        }
        return $this->_eventManager;
    }

    public function bootstrap($name, $hook)
    {
        $this->_bootstrap->addResource($name, $hook);
    }

    public function getBootstrap()
    {
        return $this->_bootstrap;
    }

    public function dispatch($uri)
    {
        $router = new Route();
        $routeObj = $router->explode($uri);
        $routeObj->addParams($_GET);
        $routeObj->addParams($_POST);

        $this->getEventManager()->publish("pre.dispatch", array('route' => $routeObj, 'application' => $this));

        $route = $routeObj->getRoute();
        $protoView = ($this->getBootstrap()->getResource("view")) ?  $this->getBootstrap()->getResource("view") : new View();

        $dispatcher = new Dispatcher($protoView);
        $dispatcher->setBootstrap($this->_bootstrap);
        $dispatcher->setControllerPath($this->getControllerPath());

        try {
            $this->_page = $dispatcher->dispatch($routeObj);
        } catch (RuntimeException $e) {
            $errorRoute = new Route();
            $errorRoute->addParams(
                array(
                    'exception' => $e
                )
            );
            //TODO add error headers

            $this->_page = $dispatcher->dispatch($errorRoute->explode("error/error"));
        }
    }

    public function run($uri = false)
    {
        $outputBuffer = '';
        $this->getEventManager()->publish("loop.startup", array($this));

        $uri = (!$uri) ? $_SERVER["REQUEST_URI"] : $uri;
        $this->dispatch($uri);

        if (($layout = $this->getBootstrap()->getResource("layout")) instanceof Layout) {
            $layout->content = $this->_page;

            $outputBuffer = $layout->render($layout->getScriptName());
        } else {
            $outputBuffer = $this->_page;
        }

        $this->getEventManager()->publish("loop.shutdown", array($this));

        $this->sendHeaders();
        echo $outputBuffer;
    }

    public function addRequest($uri)
    {
        $this->_requests[] = $uri;
    }

    public function sendHeaders()
    {
        $headers = $this->getHeaders();
        foreach ($headers as $header) {
            header($header["string"], $header["replace"], $header["code"]);
        }
    }

    public function clearHeaders()
    {
        $this->_headers = array();
    }

    public function addHeader($key, $value, $httpCode = 200, $replace  = true)
    {
        $this->_headers[] = array('string' => "{$key}:{$value}", "replace" => $replace, "code" => (int)$httpCode);
    }

    public function getHeaders()
    {
        return $this->_headers;
    }
}
