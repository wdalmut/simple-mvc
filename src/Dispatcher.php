<?php

/**
 * Dispatcher
 */
class Dispatcher
{
    private $_view;

    public function __construct(View $view)
    {
        $this->_view = $view;
    }
    /**
     * dispatch an action
     */
    public function dispatch(Route $route)
    {
        $controllerClassName = ucfirst($route["controller"]) . "Controller";
        $action = $route["action"] . "Action";
        $classPath = $controllerPath . DIRECTORY_SEPARATOR . $controllerClassName . ".php";
        $viewPath = $route["controller-clear"] . DIRECTORY_SEPARATOR . $route["action-clear"] . $protoView->getViewExt();

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
    }
}
