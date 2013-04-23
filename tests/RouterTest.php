<?php

require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/Request.php';

require_once __DIR__ . '/../exts/HostnameRouter.php';
require_once __DIR__ . '/../exts/StaticRouter.php';

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
        $hostnameRouter = new HostnameRouter("t.test.local");
        $hostnameRouter->addChild("hello", new StaticRouter("/", "Hello", "world"));

        $router->addChild("hostname", $hostnameRouter);

        $route = $router->match($request);

        $this->assertEquals("Hello", $route->getControllerName());
    }

    public function testSlashExplode()
    {
        $routeObj = $this->_object->match(new Request("/"));

        $route = $routeObj->getRoute();
        $params = $routeObj->getParams();

        $this->assertEquals("Index", $route["controller"]);
        $this->assertEquals("index", $route["action"]);

        $this->assertInternalType("array", $params);
        $this->assertSame(0, count($params));
    }

    public function testOnlyControllerExplode()
    {
        $routeObj = $this->_object->match(new Request("/home"));

        $route = $routeObj->getRoute();
        $params = $routeObj->getParams();

        $this->assertEquals("Home", $route["controller"]);
        $this->assertEquals("index", $route["action"]);

        $this->assertInternalType("array", $params);
        $this->assertSame(0, count($params));
    }

    public function testControllerActionExplode()
    {
        $routeObj = $this->_object->match(new Request("/admin/home"));

        $route = $routeObj->getRoute();
        $params = $routeObj->getParams();

        $this->assertEquals("Admin", $route["controller"]);
        $this->assertEquals("home", $route["action"]);

        $this->assertInternalType("array", $params);
        $this->assertSame(0, count($params));
    }

    public function testParamsControllerActionExplode()
    {
        $r = new Request("/walk/on/area/bar/status/inlove");
        $routeObj = $this->_object->match($r);

        $route = $routeObj->getRoute();
        $params = $r->getParams();

        $this->assertEquals("Walk", $route["controller"]);
        $this->assertEquals("on", $route["action"]);

        $this->assertInternalType("array", $params);
        $this->assertSame(2, count($params));

        $keys = array_keys($params);
        $this->assertEquals("area", $keys[0]);
        $this->assertEquals("status", $keys[1]);

        $this->assertEquals("bar", $params["area"]);
        $this->assertEquals("inlove", $params["status"]);
    }

    public function testUnbalancedParamsExplode()
    {
        $r = new Request("/walk/on/area/bar/status");
        $routeObj = $this->_object->match($r);

        $route = $routeObj->getRoute();
        $params = $r->getParams();

        $this->assertEquals("Walk", $route["controller"]);
        $this->assertEquals("on", $route["action"]);

        $this->assertInternalType("array", $params);
        $this->assertSame(1, count($params));

        $keys = array_keys($params);
        $this->assertEquals("area", $keys[0]);

        $this->assertEquals("bar", $params["area"]);
    }

    public function testComplexControllerName()
    {
        $r = new Request("/walk-on-files/hello-sunny-day/param/ok-this");
        $routeObj = $this->_object->match($r);

        $route = $routeObj->getRoute();
        $params = $r->getParams();

        $this->assertEquals("WalkOnFiles", $route["controller"]);
        $this->assertEquals("helloSunnyDay", $route["action"]);

        $params = $r->getParams();
        $this->assertEquals("ok-this", $params["param"]);
    }

    public function testClearLongGetParams()
    {
        $uri = '/?hello=world&titti=totti';
        $route = $this->_object->match(new Request($uri));
        $this->assertEquals("Index", $route->getControllerName());
        $this->assertEquals("index", $route->getActionName());
    }

    public function testClearGet2Params()
    {
        $uri = '/account?hello=world';
        $route = $this->_object->match(new Request($uri));
        $this->assertEquals("Account", $route->getControllerName());
        $this->assertEquals("index", $route->getActionName());
    }

    public function testClearGet3Params()
    {
        $uri = '/admin/account-super?hello=world';
        $route = $this->_object->match(new Request($uri));
        $this->assertEquals("Admin", $route->getControllerName());
        $this->assertEquals("accountSuper", $route->getActionName());
    }
}
