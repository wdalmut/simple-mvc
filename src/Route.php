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

    public function explode($uri)
    {
        if (!is_string($uri) || empty($uri)) {
            throw new RuntimeException("URI must be a string");
        }

        $uri = (strpos($uri, "?") !== false) ? substr($uri, 0, strpos($uri, "?")) : $uri;
        $parts = explode($this->_delimiter, $uri);

        $parts = $this->_filter($parts);

        switch (count($parts)) {
            case 0:
                $this->setControllerName("index");
                $this->setActionName("index");
                break;
            case 1:
                $this->setControllerName("index");
                $this->setActionName($parts[0]);
                array_shift($parts);
                break;
            default:
                $this->setControllerName($parts[0]);
                $this->setActionName($parts[1]);
                array_shift($parts);
                array_shift($parts);
                break;
        }

        (count($parts) % 2 !== 0) ? array_pop($parts) : false;

        if (count($parts)) {
            for ($i=0; $i<count($parts); $i=$i+2) {
                $this->_params[$parts[$i]] = $parts[$i+1];
            }
        }

        return $this;
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
