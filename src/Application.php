<?php 
class Application
{
    private $_bootstrap = array();
    
    public function bootstrap($name, $hook)
    {
        $this->_bootstrap[$name] = $hook;
    }
    
    public function getBootstrap($name)
    {
        $b = $this->_bootstrap[$name];
        
        return call_user_func($b);
    }
    
    public function run()
    {
        // run the right controller
    }
}