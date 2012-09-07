<?php

/**
 * Dispatcher tests
 */
class DispatcherTest extends PHPUnit_Framework_TestCase
{
    private $_object;

    function setUp()
    {
        parent::setUp();

        $this->_object = new Dispatcher(new View());
        $this->_object->setBootstrap(new Bootstrap());
        $this->_object->setControllerPath(__DIR__ . '/controllers');
    }

    public function testDispatchARoute()
    {
        $route = new Route();
        $route->explode("alone/an");

        $content = $this->_object->dispatch($route);

        $this->assertEquals("an-action", $content);
    }
}

