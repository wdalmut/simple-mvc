<?php
class Route
{
    private $_delimiter = '/';

    private $_route = array();
    private $_params = array();

    public function setControllerName($name)
    {
        $this->_route["controller"] = ucfirst($this->_toCamelCase($name));
        $this->_route["controller-clear"] = $name;
    }

    public function getControllerName()
    {
        return $this->_route["controller"];
    }

    public function getControllerPath()
    {
        return $this->_route["controller-clear"];
    }

    public function getActionName()
    {
        return $this->_route["action"];
    }

    public function setActionName($name)
    {
        $this->_route["action"] = $this->_toCamelCase($name);
        $this->_route["action-clear"] = $name;
    }

    public function getActionPath()
    {
        return $this->_route["action-clear"];
    }

    /**
     * Merge parent route with this
     *
     * @param Route $route The parent route
     *
     * @return Route mixed route
     */
    public function merge($parent)
    {
        $merged = clone $this;
        if ($parent) {
            //Merge with parent route
            ($this->getActionName()) ?
                $merged->setActionName($this->getActionName()) :
                $merged->setActionName($parent->getActioName());

            ($this->getControllerName()) ?
                $merged->setControllerName($this->getControllerName()) :
                $merged->setControllerName($parent->getControllerName());

            $this->addParams(array_merge($parent->getParams(), $this->getParams()));
        }

        return $merged;
    }

    private function _toCamelCase($part)
    {
        $pos = 0;
        while(($pos = strpos($part, "-", $pos)) !== false && $pos < strlen($part)) {
            if ($pos+1 < strlen($part)) {
                $part[$pos+1] = strtoupper($part[$pos+1]);
            }
            ++$pos;
        }
        return str_replace("-", "", $part);
    }

    public function getRoute()
    {
        return $this->_route;
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function addParams($params)
    {
        $this->_params = array_merge($this->_params, $params);
    }
}
