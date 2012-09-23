<?php

/**
 * Dispatcher tests
 */
class DispatcherTest extends PHPUnit_Framework_TestCase
{
    private $object;

    function setUp()
    {
        parent::setUp();

        $this->object = new Dispatcher(new View());
        $this->object->setBootstrap(new Bootstrap());
        $this->object->setEventManager(new EventManager());
        $this->object->setControllerPath(__DIR__ . '/controllers');
        $router = new Router();
        $this->object->setRouter($router);
    }

    public function testDispatchARoute()
    {
        $request = new Request("alone/an");
        $this->object->setRequest($request);
        $route = $this->object->getRouter()->match($request);
        $content = $this->object->dispatch($route);

        $this->assertEquals("an-action", $content);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testDispatchAnError()
    {
        $request = new Request("/not/exists-this-action");
        $this->object->setRequest($request);
        $route = $this->object->getRouter()->match($request);
        $this->object->dispatch($route);
    }

    public function testHeaderCodes()
    {
        $this->object->addHeader("content-type", "text/html", "202");

        $headers = $this->object->getHeaders();
        $this->assertCount(1, $headers);
        $this->assertSame(202, $headers[0]["code"]);
    }

    public function testSetGetHeaders()
    {
        $this->object->addHeader("content-type", "text/html");

        $headers = $this->object->getHeaders();
        $this->assertCount(1, $headers);

        $this->object->addHeader("content-disposition", "inline;");
        $headers = $this->object->getHeaders();
        $this->assertCount(2, $headers);

        $this->assertStringStartsWith("content-type", $headers[0]["string"]);
        $this->assertStringEndsWith("text/html", $headers[0]["string"]);

        $this->assertStringStartsWith("content-disposition", $headers[1]["string"]);
        $this->assertStringEndsWith("inline;", $headers[1]["string"]);
    }
}

