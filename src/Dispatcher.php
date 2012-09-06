<?php

/**
 * Dispatcher
 */
class Dispatcher
{
    private $_view;
    private $_actions;
    private $_bootstrap;
    private $_controllerPath;

    private $_viewsQueue;

    public function __construct(View $view)
    {
        $this->_view = $view;
        $this->_actions = array();
        $this->_viewsQueue = array();
    }

    public function setControllerPath($path)
    {
        $this->_controllerPath = $path;
    }

    public function getControllerPath()
    {
        return $this->_controllerPath;
    }

    public function setBootstrap($bootstrap)
    {
        $this->_bootstrap = $bootstrap;
    }

    public function getBootstrap()
    {
        return $this->_bootstrap;
    }

    public function add(Route $route)
    {
        $this->_actions[] = $route;
    }

    /**
     * dispatch an action
     */
    public function dispatch(Route $route)
    {
        do {
            $protoView = $this->_view->cloneThis();
            $controllerClassName = $route->getControllerName() . "Controller";
            $action = $route->getActionName() . "Action";
            $classPath = $this->getControllerPath() . DIRECTORY_SEPARATOR . $controllerClassName . ".php";
            $viewPath = $route->getControllerPath()
                . DIRECTORY_SEPARATOR
                . $route->getActionPath()
                . $protoView->getViewExt();

            if (!file_exists($classPath)) {
                // Use base controller
                $controllerClassName = 'Controller';
            } else {
                require_once $classPath;
            }

            $controller = new $controllerClassName($this);
            $controller->setParams(
                array_merge(
                    array(
                        "dispatcher" => &$this,
                        "bootstrap" => $this->getBootstrap()
                    ),
                    $route->getParams()));
            $controller->setRawBody(@file_get_contents('php://input'));

            $controller->setView($protoView->cloneThis());
            $controller->view->controllerPath = $this->getControllerPath();

            if (method_exists($controller, $action)) {
                ob_start();
                $controller->init();
                $controller->$action();
                array_push($this->_viewsQueue, ob_get_contents());
                ob_end_clean();
            } else {
                if (!file_exists($controller->view->getViewPath() . DIRECTORY_SEPARATOR . $viewPath)) {
                    throw new RuntimeException("Page not found {$route->getControllerName()} -> {$route->getActionName()}", 404);
                }
            }
        } while(($route = array_shift($this->_actions)));

        return implode("", $this->_viewsQueue);
    }
}
