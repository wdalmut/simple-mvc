<?php 
/**
 * 
 * The main Application
 *
 * @author Walter Dal Mut
 * @package 
 * @license MIT
 *
 * Copyright (C) 2012 Corley S.R.L.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class Application
{
    private $_bootstrap = array();
    
    /**
     * Add a bootstrap step
     * 
     * @param string $name
     * @param callback $hook The closure for bootstrap
     */
    public function bootstrap($name, $hook)
    {
        $this->_bootstrap[$name] = $hook;
    }
    
    /**
     * Retrive a bootstrapped object
     * 
     * @param string $name The object name
     * @return mixed The bootstrapped object result
     */
    public function getBootstrap($name)
    {
        $b = $this->_bootstrap[$name];
        
        return call_user_func($b);
    }
    
    /**
     * Starts the app
     * 
     * @param string|bolean $uri The URI to parse of false for REQUEST_URI
     * 
     * @todo Refactor this method. Difficult test it.
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
        
        $controller->$action();
        
        if ($controller->getView()) {
            $content = $controller->getView()->render(
                $route["controller"] . DIRECTORY_SEPARATOR . $route["action"] . ".phtml"
            );
            
            if (($layout = $this->getBootstrap("layout")) != false) {
                $layout->content = $content;
                
                echo $layout->render("layout.phtml");
            }
        }
    }
}