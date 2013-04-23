<?php
class Router
{
    private $_childs;

    public function addChild($name, Router $router)
    {
        $this->_childs[$name] = $router;
    }

    /**
     * Match a request on a route
     *
     * @param Request the actual request
     * @return Route
     */
    public function match(Request $request, $route = false)
    {
        if (count($this->_childs)) {
            $route = $this->_match($request, $route);
        }

        if (!$route) {
            $route = $this->_default($request)->merge($route);
        }


        if (!$route) {
            throw new RuntimeException("Missing route...");
        }

        return $route;
    }

    protected function _match($request, $route)
    {
        foreach ($this->_childs as $router) {
            $route = $router->match($request, $route);
            if ($route instanceOf Route) {
                return $route;
            }
        }

        return false;
    }

    private function _filter($parts)
    {
        $clean = array();
        foreach ($parts as $part) {
            $part = trim($part);
            if (!empty($part)) {
                $clean[] = $part;
            }
        }

        return $clean;
    }


    protected function _default(Request $request)
    {
        $route = new Route();
        $uri = $request->getUri();

        $uri = (strpos($uri, "?") !== false) ? substr($uri, 0, strpos($uri, "?")) : $uri;
        $parts = explode("/", $uri);

        $parts = $this->_filter($parts);

        switch (count($parts)) {
            case 0:
                $route->setControllerName("index");
                $route->setActionName("index");
                break;
            case 1:
                $route->setControllerName($parts[0]);
                $route->setActionName("index");
                array_shift($parts);
                break;
            default:
                $route->setControllerName($parts[0]);
                $route->setActionName($parts[1]);
                array_shift($parts);
                array_shift($parts);
                break;
        }

        (count($parts) % 2 !== 0) ? array_pop($parts) : false;

        if (count($parts)) {
            for ($i=0; $i<count($parts); $i=$i+2) {
                $request->addParam($parts[$i], $parts[$i+1]);
            }
        }

        return $route;
    }


    public function assemble($params, $name = false)
    {
        if (!$name) {
            //Assemble this route
        } else {
            return $this->_childs[$name]->assemble($params);
        }
    }
}
