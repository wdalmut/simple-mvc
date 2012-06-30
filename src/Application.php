<?php 
class Application
{
    private $_controllerPath = '../controllers';
    private $_bootstrap = array();
    private $_eventManager;
    private $_views = array();
    private $_headers = array();
    private $_requests = array();
    
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
        $controllerPath = $this->_controllerPath;
        
        $router = new Route();
        $routeObj = $router->explode($uri);
        $routeObj->addParams($_GET);
        $routeObj->addParams($_POST);
        
        $this->getEventManager()->publish("pre.dispatch", array('route' => $routeObj, 'application' => $this));
        
        $route = $routeObj->getRoute();
        
        $protoView = ($this->getBootstrap("view")) ?  $this->getBootstrap("view") : new View();
        $protoView->addHelper("pull", function($uri) use ($controllerPath) {
            $router = new Route();
            $routeObj = $router->explode($uri);
        
            $route = $routeObj->getRoute();
        
            $controllerClassName = ucfirst($route["controller"]) . "Controller";
            $action = $route["action"] . "Action";
            $classPath = realpath($controllerPath . DIRECTORY_SEPARATOR . $controllerClassName . ".php");
            if (file_exists($classPath)) {
                require_once $classPath;
                
                $controller = new $controllerClassName();
                $controller->setParams($routeObj->getParams());
            
                if (method_exists($controller, $action)) {
                    ob_start();
                    $controller->init();
                    return $controller->$action();
                    ob_end_clean();
                } else {
                    throw new RuntimeException("Pull operation {$route["controller-clear"]}/{$route["action-clear"]} failed.", 404);
                }
            } else {
                throw new RuntimeException("Pull operation {$route["controller-clear"]}/{$route["action-clear"]} failed.", 404);
            }
        });
        
        $controllerClassName = ucfirst($route["controller"]) . "Controller";
        $action = $route["action"] . "Action";
        $classPath = $controllerPath . DIRECTORY_SEPARATOR . $controllerClassName . ".php";
        $viewPath = $route["controller-clear"] . DIRECTORY_SEPARATOR . $route["action-clear"] . ".phtml";
        
        if (!file_exists($classPath)) {
            // Use base controller
            $controllerClassName = 'Controller';
        } else {
            require_once $classPath;
        }
        
        $controller = new $controllerClassName($this);
        $controller->setParams($routeObj->getParams());
        $controller->setRawBody(@file_get_contents('php://input'));
        
        $controller->setView($protoView->cloneThis());
        $controller->view->controllerPath = $this->_controllerPath;
        (($layout = $this->getBootstrap('layout'))) ? $controller->view->addHelpers($layout->getHelpers()) : false;
        
        if (method_exists($controller, $action)) {
            ob_start();
            $controller->init();
            $controller->$action();
            $content = ob_get_contents();
            array_push($this->_views, $content);
            ob_end_clean();
        } else {
            if (!file_exists($controller->view->getViewPath() . DIRECTORY_SEPARATOR . $viewPath)) {
                throw new RuntimeException("Page not found {$route["controller-clear"]}/{$route["action-clear"]}", 404);
            }
        }
        
        $this->getEventManager()->publish("post.dispatch", array('controller' => $controller));
        
        if ($controller->view->getViewPath()) {
            array_push(
                $this->_views, 
                $controller->getView()->render(
                    (($controller->getViewPath() !== false) ? $controller->getViewPath() : $viewPath))
            );
        }
    }
    
    public function run($uri = false)
    {
        $outputBuffer = '';
        $this->getEventManager()->publish("loop.startup", array($this));
        
        try {
            $uri = (!$uri) ? $_SERVER["REQUEST_URI"] : $uri;
            do {
                $this->dispatch($uri);
            } while(($uri = array_shift($this->_requests)));
        } catch (RuntimeException $e) {
            $this->clearHeaders();
            $this->addHeader("Content-Type", "text/html", 500);
            $this->dispatch("/error/error");
        }
         
        if (($layout = $this->getBootstrap("layout")) instanceof Layout) {
            $layout->content = implode("", $this->_views);
        
            $outputBuffer = $layout->render($layout->getScriptName());
        } else {
            $outputBuffer = implode("", $this->_views);
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