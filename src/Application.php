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
        if (array_key_exists($name, $this->_bootstrap)) {
            $b = $this->_bootstrap[$name];
            
            if (is_callable($b)) {
                $this->_bootstrap[$name] = call_user_func($b);
            } 
            
            return $this->_bootstrap[$name];
        } else {
            return false;
        }
    }
    
    public function dispatch($uri) 
    {
        // run the right controller
        $router = new Route();
        $routeObj = $router->explode($uri);
        
        $route = $routeObj->getRoute();
        
        $this->getEventManager()->publish("pre.dispatch", array('route' => $route));
        
        $controllerClassName = ucfirst($route["controller"]) . "Controller";
        $action = $route["action"] . "Action";
        $classPath = $this->_controllerPath . DIRECTORY_SEPARATOR . $controllerClassName . ".php";
        
        if (!file_exists($classPath)) {
            throw new RuntimeException("Page not found {$route["controller"]}/{$route["action"]}", 404);
        } else {
            require_once $classPath;
        }
        
        $controller = new $controllerClassName($this);
        $controller->setApplication($this);
        $controller->setParams($routeObj->getParams());
        
        if ($this->getBootstrap("view")) {
            $controller->setView($this->getBootstrap("view")->cloneThis());
        }
        
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            throw new RuntimeException("Page not found {$route["controller"]}/{$route["action"]}", 404);
        }
        
        $this->getEventManager()->publish("post.dispatch", array('controller' => $controller));
        
        if ($controller->getView()) {
            array_unshift($this->_views, $controller->getView()->render(
                $route["controller"] . DIRECTORY_SEPARATOR . $route["action"] . ".phtml"
            ));
        }
    }
    
    public function run($uri = false)
    {
        try {
            $this->getEventManager()->publish("loop.startup");
            
            $uri = (!$uri) ? $_SERVER["REQUEST_URI"] : $uri; 
            $this->dispatch($uri);
            
            $this->getEventManager()->publish("loop.shutdown");
        } catch (RuntimeException $e) {
            $this->dispatch("/error/error");
        }
         
        if (($layout = $this->getBootstrap("layout")) != false) {
            $layout->content = implode("", $this->_views);
        
            echo $layout->render($layout->getScriptName());
        } else {
            echo implode("", $this->_views);
        }
    }
}