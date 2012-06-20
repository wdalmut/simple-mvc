<?php 
class Controller
{
    /**
     * 
     * @var Application
     */
    private $_application;
    
    public function __construct($bootstrap)
    {
        $this->setBootstrap($bootstrap);
        
        // Run the init
        $this->init();
    }
    
    public function init(){}
    
    public function setApplication(Application $application)
    {
        $this->_application = $application;
    }
    
    public function getResource($name)
    {
        return $this->_application->getBootstrap($name);
    }
}