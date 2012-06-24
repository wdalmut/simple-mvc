<?php 
class View
{
    private $_path;
    private $_charset = 'utf-8';
    private $_data = array();
    public $controllerPath;
    
    private $_helpers = array();
    
    public function __construct()
    {
        $cs = $this->_charset;
        $this->addHelper("escape", function($text, $flags = ENT_COMPAT, $charset = null, $doubleEncode = true) use ($cs) {
            return htmlspecialchars($text, $flags, $charset ?: $cs, $doubleEncode);
        });
        
        $view = $this;
        
        $this->addHelper("pull", function($uri) use ($view) {
            $router = new Route();
            $routeObj = $router->explode($uri);
            
            $route = $routeObj->getRoute();
            
            $controllerClassName = ucfirst($route["controller"]) . "Controller";
            $action = $route["action"] . "Action";
            $classPath = $view->controllerPath . DIRECTORY_SEPARATOR . $controllerClassName . ".php";
            
            $controller = new $controllerClassName(new Application());
            $controller->setParams($routeObj->getParams());
            
            if (method_exists($controller, $action)) {
                ob_start();
                $controller->init();
                return $controller->$action();
                ob_end_clean();
            } else {
                throw new RuntimeException("Pull operation {$route["controller-clear"]}/{$route["action-clear"]} failed.", 404);
            }
        });
    }
     
    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }
    
    public function __get($key)
    {
        return (isset($this->_data[$key])) ? $this->_data[$key] : false; 
    }
    
    public function __call($method, $args) 
    {
        if (array_key_exists($method, $this->_helpers)) {
            return call_user_func_array($this->_helpers[$method], $args);
        } else {
            throw new RuntimeException("Helper view {$method} doesn't exists. Add it using addHelper method.");
        }
    }
    
    public function setViewPath($path)
    {
        if (!is_dir($path)) {
            throw new RuntimeException("View path {$path} must be a directory");
        }
        $this->_path = $path;
    }
    
    public function getViewPath()
    {
        return $this->_path;
    }
    
    public function render($filename, $data = false)
    {
        if($data) {
            if (!is_array($data)) {
                throw new RuntimeException("You must pass an array to data view.");
            }
            $this->_data = array_merge($this->_data, $data);
        }
    
        if(!$this->_path) {
            $this->setViewPath(dirname(__FILE__));
        }
    
        $filename = $this->_path . "/" . $filename ;
        if (!file_exists($filename)) {
            throw new RuntimeException("Unable to get view at path: {$filename}");
        }
    
        $rendered = "";
    
        ob_start();
        require($filename);
        $rendered = ob_get_contents();
        ob_end_clean();
    
        return $rendered;
    }
    
    public function cloneThis()
    {
        return clone($this);
    }
    
    public function addHelper($name, $helper) 
    {
        $this->_helpers[$name] = $helper;
    }
    
    public function addHelpers(array $helpers)
    {
        $this->_helpers = array_merge($this->_helpers, $helpers);
    }
    
    public function getHelpers()
    {
        return $this->_helpers;
    }
}