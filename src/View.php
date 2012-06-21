<?php 
class View
{
    private $_path;
    
    private $_data = array();
     
    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }
    
    public function __get($key)
    {
        if(isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        else {
            return false;
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
        //Check if data exists
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
}