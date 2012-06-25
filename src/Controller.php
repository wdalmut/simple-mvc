<?php 
class Controller
{
    private $_application;
    
    private $_params;
    private $_rawBody;
    
    public $view;
    
    public function __construct($application = null)
    {
        $application = (!$application) ? new Application() : $application;
        $this->setApplication($application);
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
    
    public function setApplication($application)
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
    
    public function setRawBody($body)
    {
        $this->_rawBody = $body;
    }
    
    public function getRawBody()
    {
        return $this->_rawBody;
    }
    
    public function then($uri)
    {
        $this->_application->addRequest($uri);
    }
    
    public function clearHeaders()
    {
        $this->_application->clearHeaders();
    }
    
    public function addHeader($key, $value, $httpCode = 200, $replace  = true)
    {
        $this->_application->addHeader($key, $value, $httpCode, $replace);
        return $this;
    }
    
    public function redirect($url, $header=301)
    {
        $this->disableLayout();
        $this->setNoRender();
        
        $this->_application->clearHeaders();
        $this->_application->addHeader("Location", $url, $header);
    }
    
    public function disableLayout()
    {
        $this->_application->bootstrap("layout", function(){return false;});
    }
    
    public function setNoRender()
    {
        $this->view = new View();
    }
}