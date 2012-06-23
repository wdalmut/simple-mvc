<?php
class Route
{
    private $_delimiter;
    
    private $_route;
    private $_params;
    
    public function __construct()
    {
        $this->_delimiter = "/";
    }
    
    public function explode($uri)
    {
        if (!is_string($uri)) {
            throw new RuntimeException("URI must be a string");
        }
        
        $this->_route = array();
        $this->_params = array();
        
        $parts = explode($this->_delimiter, $uri);
        
        $parts = $this->_filter($parts);
        
        switch (count($parts)) {
            case 0:
                $this->_route["controller"] = "index";
                $this->_route["action"] = "index";
                $this->_route["action-clear"] = "index";
                $this->_route["controller-clear"] = "index";
                break;
            case 1:
                $this->_route["controller"] = "index";
                $this->_route["action"] = $this->_toCamelCase($parts[0]);
                $this->_route["controller-clear"] = "index";
                $this->_route["action-clear"] = $parts[0];
                array_shift($parts);
                break;
            default:
                $this->_route["controller"] = $this->_toCamelCase($parts[0]);
                $this->_route["action"] = $this->_toCamelCase($parts[1]);
                $this->_route["controller-clear"] = $parts[0];
                $this->_route["action-clear"] = $parts[1];
                array_shift($parts);
                array_shift($parts);
                
                break;
        }
        
        $this->_route["controller"] = ucfirst($this->_route["controller"]);
        
        if (count($parts) % 2 !== 0) {
            array_pop($parts);
        }
        
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
}