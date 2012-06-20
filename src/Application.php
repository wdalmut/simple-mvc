<?php 
class Application
{
    private $_bootstrap = array();
    
    public function bootstrap($name, $hook)
    {
        $this->_bootstrap[$name] = $hook;
    }
    
    public function getBootstrap($name)
    {
        $b = $this->_bootstrap[$name];
        
        return call_user_func($b);
    }
    
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
        
        $controller->setView($view);
        
        $controller->$action();
        
        echo $controller->getView()->render(
            $route["controller"] . DIRECTORY_SEPARATOR . $route["action"] . ".phtml"
        );
    }
}