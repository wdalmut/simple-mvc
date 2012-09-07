<?php

/**
 * Bootstrap tests
 */
class BootstrapTest extends PHPUnit_Framework_TestCase
{
    private $object;

    function setUp()
    {
        $this->object = new Bootstrap();
    }


    /**
     * @covers Application::bootstrap
     * @covers Application::getBootstrap
     */
    public function testBootstrap()
    {
        $this->object->addResource("hello", function(){return "ciao";});
        $boot = $this->object->getResource("hello");

        $this->assertEquals($boot, "ciao");
    }

    /**
     * Resources must bootstrap onetime
     *
     * @covers Application::getBootstrap
     */
    public function testGetMultipleTimes()
    {
        $this->object->addResource("hello", function(){
            return new View();
        });
        $boot = $this->object->getResource("hello");
        $boot2 = $this->object->getResource("hello");

        $this->assertInstanceOf("View", $boot);

        $this->assertSame($boot, $boot2);
    }
}

