<?php 
class Application
{
    private $_bootstrap = array();

    private $_eventManager;
    
    private $_views = array();
    
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
        
        return call_user_func($b);
    }
    
    public function dispatch($uri) 
    {
        // run the right controller
        $router = new Route();
        $routeObj = $router->explode($uri);
        
        $route = $routeObj->getRoute();
        $controllerClassName = ucfirst($route["controller"]) . "Controller";
        
        $action = $route["action"] . "Action";
        
        //TODO: use autoloader instead
        require_once $controllerClassName . ".php";
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
    
    /**
     * 
     * @todo Refactor this method. I can't test it!
     * 
     * @param string $uri the URL
     */
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