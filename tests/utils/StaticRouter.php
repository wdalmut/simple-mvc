<?php
class StaticRouter extends Router
{
    private $_action;
    private $_controller;
    private $_path;

    public function __construct($path, $controller, $action)
    {
        $this->_path = $path;
        $this->_action = $action;
        $this->_controller = $controller;
    }

    public function match(Request $request, $route = false)
    {
        if ($request->getUri() == $this->_path) {
            $static = new Route();
            $static->setControllerName($this->_controller);
            $static->setActionName($this->_action);
            $static->merge($route);

            return parent::match($request, $static);
        }
    }
}
