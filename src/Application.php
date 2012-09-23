<?php
class Application
{
    private $_controllerPath = '../controllers';
    private $_bootstrap;
    private $_eventManager;
    private $_page = '';

    public function __construct(Bootstrap $bootstrap = null, EventManager $eventManager = null)
    {
        $this->_bootstrap = ($bootstrap) ? $bootstrap : new Bootstrap();
        $this->_eventManager = ($eventManager) ? $eventManager : new EventManager();
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

    public function dispatch(Route $route)
    {
        $protoView = ($this->getBootstrap()->getResource("view")) ?  $this->getBootstrap()->getResource("view") : new View();

        $controllerPath = $this->getControllerPath();
        $protoView->addHelper("pull", function($uri) use ($controllerPath) {
            return;
            $request = clone $request;
            $request->setUri($uri);
            $routeObj = $router->match($request);

            $controllerClassName = $routeObj->getControllerName() . "Controller";
            $action = $routeObj->getActionName() . "Action";
            $classPath = realpath($controllerPath . DIRECTORY_SEPARATOR . $controllerClassName . ".php");
            if (file_exists($classPath)) {
                require_once $classPath;

                $controller = new $controllerClassName();
                $controller->setParams($routeObj->getParams());

                if (method_exists($controller, $action)) {
                    ob_start();
                    $controller->init();
                    $data = $controller->$action();
                    ob_end_clean();
                    return $data;
                } else {
                    throw new RuntimeException("Pull operation {$routeObj->getControllerName()} - {$routeObj->getActionName()} failed.", 404);
                }
            } else {
                throw new RuntimeException("Pull operation {$routeObj->getControllerName()} - {$routeObj->getActionName()} failed.", 404);
            }
        });

        $dispatcher = new Dispatcher($protoView);
        $dispatcher->setEventManager($this->getEventManager());
        $dispatcher->setBootstrap($this->_bootstrap);
        $dispatcher->setControllerPath($this->getControllerPath());

        try {
            $this->_page = $dispatcher->dispatch($route);
        } catch (RuntimeException $e) {
            $errorRoute = new Route();
            $errorRoute->addParams(
                array(
                    'exception' => $e
                )
            );

            $dispatcher->clearHeaders();
            $dispatcher->addHeader("","",404);

            $errorRoute->setControllerName("error");
            $errorRoute->setActionName("error");

            $this->_page = $dispatcher->dispatch($errorRoute);
        }

        return array('headers' => $dispatcher->getHeaders());
    }

    public function run(Request $request = null)
    {
        $router = ($this->getBootstrap()->getResource("router")) ? $this->getResource("router") : new Router();
        $request = (!$request) ? Request::newHttp() : $request;

        $outputBuffer = '';
        $this->getEventManager()->publish("loop.startup", array($this));

        $status = $this->dispatch($router->match($request));

        if (($layout = $this->getBootstrap()->getResource("layout")) instanceof Layout) {
            $layout->content = $this->_page;

            $outputBuffer = $layout->render($layout->getScriptName());
        } else {
            $outputBuffer = $this->_page;
        }

        $this->getEventManager()->publish("loop.shutdown", array($this));

        $this->sendHeaders($status["headers"]);
        echo $outputBuffer;
    }

    public function addRequest($uri)
    {
        $this->_requests[] = $uri;
    }

    public function sendHeaders($headers)
    {
        foreach ($headers as $header) {
            header($header["string"], $header["replace"], $header["code"]);
        }
    }
}
