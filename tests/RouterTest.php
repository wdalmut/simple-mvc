<?php

require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/Request.php';

require_once __DIR__ . '/utils/HttpRouter.php';
require_once __DIR__ . '/utils/StaticRouter.php';

class RouterTest extends PHPUnit_Framework_TestCase
{
    private $_object;

    public function setUp()
    {
        $this->_object = new Router();
    }

    public function testDefaultRouting()
    {
        $request = new Request();
        $request->setUri("/controller/action");
        $route = $this->_object->match($request);

        $this->assertEquals("Controller", $route->getControllerName());
        $this->assertEquals("action", $route->getActionName());
    }

    public function testDefaultRoutingComplexNaming()
    {
        $request = new Request();
        $request->setUri("/controller-name/action-name-hello");
        $route = $this->_object->match($request);

        $this->assertEquals("ControllerName", $route->getControllerName());
        $this->assertEquals("actionNameHello", $route->getActionName());
    }

    public function testChain()
    {
        $request = new Request();
        $request->setHostname("t.test.local");
        $request->setUri("/");

        $router = new Router();
        $hostnameRouter = new HttpRouter("t.test.local");
        $hostnameRouter->addChild("hello", new StaticRouter("/", "Hello", "world"));

        $router->addChild("hostname", $hostnameRouter);

        $route = $router->match($request);

        $this->assertEquals("Hello", $route->getControllerName());
    }
}
