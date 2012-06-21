<?php 
class Application
{
    private $_bootstrap = array();

    private $_eventManager;
    
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
    
    /**
     * 
     * @todo Refactor this method. I can't test it!
     * 
     * @param string $uri the URL
     */
    public function run($uri = false)
    {
        if (!$uri) {
            $uri = $_SERVER["REQUEST_URI"];
        }
        
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
        
        if (($view = $this->getBootstrap("view")) !== false) {
            $controller->setView($view);
        }
        
        $this->getEventManager()->publish("pre.dispatch", array('controller' => $controller));
        $controller->$action();
        $this->getEventManager()->publish("post.dispatch", array('controller' => $controller));
        
        if ($controller->getView()) {
            $content = $controller->getView()->render(
                $route["controller"] . DIRECTORY_SEPARATOR . $route["action"] . ".phtml"
            );
            
            if (($layout = $this->getBootstrap("layout")) != false) {
                $layout->content = $content;
                
                echo $layout->render($layout->getScriptName());
            }
        }
    }
}