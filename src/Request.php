<?php
class Request
{
    private $_hostname;
    private $_uri;
    private $_getParams;
    private $_postParams;
    private $_params;
    private $_rawBody;

    public function setHostname($hostname)
    {
        $this->_hostname = $hostname;
    }

    public function getHostname()
    {
        return $this->_hostname;
    }

    public function setUri($uri)
    {
        $this->_uri = $uri;
    }

    public function getUri()
    {
        return $this->_uri;
    }

    public function setGetParams($params)
    {
        $this->_getParams = $params;
    }

    public function getGetParams()
    {
        return $this->_getParams;
    }

    public function setPostParams($params)
    {
        $this->_postParams($params);
    }

    public function getPostParams()
    {
        return $this->_postParams;
    }

    public function setParams(array $params)
    {
        $this->_params = $params;
    }

    public function addParam($key, $value)
    {
        $this->_params[$key] = $value;
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

    public static function newHttp()
    {
        $request = new Request();
        $request->setHostname($_SERVER["SERVER_NAME"]);
        $request->setUri("REQUEST_URI");
        $request->setGetParams($_GET);
        $request->setPostParams($_POST);
        $request->setRawBody(@file_get_contents('php://input'));

        return $request;
    }
}
