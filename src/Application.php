<?php 
class Application
{
    private $_controllerPath = '../controllers';
    
    private $_bootstrap = array();

    private $_eventManager;
    
    private $_views = array();
    
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
        if (!is_callable($hook)) {
            throw new RuntimeException("Hook must be callable");
        }
        
        $this->_bootstrap[$name] = $hook;
    }
    
    public function getBootstrap($name)
    {
        $b = $this->_bootstrap[$name];
        
        if (is_callable($b)) {
            $this->_bootstrap[$name] = call_user_func($b);
        } 
        
        return $this->_bootstrap[$name];
    }
    
    public function dispatch($uri) 
    {
        // run the right controller
        $router = new Route();
        $routeObj = $router->explode($uri);
        
        $route = $routeObj->getRoute();
        $controllerClassName = ucfirst($route["controller"]) . "Controller";
        
        $action = $route["action"] . "Action";
        
        require_once $this->_controllerPath . DIRECTORY_SEPARATOR . $controllerClassName . ".php";
        $controller = new $controllerClassName($this);
        
        $controller->setApplication($this);
        $controller->setParams($routeObj->getParams());
        
        $controller->setView($this->getBootstrap("view"));
        
        $this->getEventManager()->publish("pre.dispatch", array('controller' => $controller));
        $controller->$action();
        $this->getEventManager()->publish("post.dispatch", array('controller' => $controller));
        
        if ($controller->getView()) {
            array_unshift($this->_views, $controller->getView()->render(
                $route["controller"] . DIRECTORY_SEPARATOR . $route["action"] . ".phtml"
            ));
        }
    }
    
    public function run($uri = false)
    {
        $this->getEventManager()->publish("loop.startup");
        
        if (!$uri) {
            $uri = $_SERVER["REQUEST_URI"];
        }
        
        $this->dispatch($uri);
        
        if (($layout = $this->getBootstrap("layout")) != false) {
            $layout->content = implode("", $this->_views);
            
            echo $layout->render($layout->getScriptName());
        } else {
            echo implode("", $this->_views);
        }
        
        $this->getEventManager()->publish("loop.shutdown");
    }
}