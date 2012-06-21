<?php 
class Controller
{
    /**
     * 
     * @var Application
     */
    private $_application;
    
    private $_params;
    
    public $view;
    
    public function __construct(Application $bootstrap)
    {
        $this->setApplication($bootstrap);
        
        // Run the init
        $this->init();
    }
    
    public function setView($view)
    {
        $this->view = $view;
    }
    
    public function getView()
    {
        return $this->view;
    }
    
    public function init(){}
    
    public function setApplication(Application $application)
    {
        $this->_application = $application;
    }
    
    public function getApplication()
    {
        return $this->_application;
    }
    
    public function getResource($name)
    {
        return $this->_application->getBootstrap($name);
    }
    
    public function setParams($params)
    {
        $this->_params = $params;
    }
    
    public function getParams()
    {
        return $this->_params;
    }
    
    public function then($uri)
    {
        return $this->_application->dispatch($uri);
    }
}