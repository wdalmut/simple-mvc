<?php
class Controller
{
    private $_params;
    private $_rawBody;
    private $_viewScript;

    public $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function getView()
    {
        return $this->view;
    }

    public function init(){}

    public function setParams($params)
    {
        $this->_params = $params;
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function getParam($key)
    {
        return (array_key_exists($key, $this->_params)) ? $this->_params[$key] : false;
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
        $route = new Route();
        $this->_params["dispatcher"]->add($route->explode($uri));
    }

    public function clearHeaders()
    {
        $this->_params["dispatcher"]->clearHeaders();
    }

    public function addHeader($key, $value, $httpCode = 200, $replace  = true)
    {
        $this->_params["dispatcher"]->addHeader($key, $value, $httpCode, $replace);
        return $this;
    }

    public function getHeaders()
    {
        return $this->_params["dispatcher"]->getHeaders();
    }

    /**
     * Using the dispatcher
     */
    public function redirect($url, $header=301)
    {
        $this->disableLayout();
        $this->setNoRender();

        $this->_params["dispatcher"]->clearHeaders();
        $this->_params["dispatcher"]->addHeader("Location", $url, $header);
    }

    public function setRenderer($renderer)
    {
        $this->_viewScript = $renderer;
    }

    public function getViewPath()
    {
        return ($this->_viewScript) ? $this->_viewScript . ".phtml" : false;
    }

    public function disableLayout()
    {
        throw new Exception("Missing strategy");
    }

    public function setNoRender()
    {
        $this->view = new View();
    }
}
