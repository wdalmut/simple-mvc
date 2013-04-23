<?php
class HostnameRouter extends Router
{
    private $_hostname;

    public function __construct($hostname)
    {
        $this->_hostname = $hostname;
    }

    public function match(Request $request, $route = false)
    {
        if ($request->getHostname() == $this->_hostname) {
            return parent::match($request, new Route());
        }

        return false;
    }
}
