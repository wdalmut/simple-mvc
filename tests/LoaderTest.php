<?php 

class LoaderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
         require_once __DIR__ . '/../src/Loader.php';
    }
    
    public function testClassmapLoading()
    {
        Loader::classmap();
        
        $this->assertTrue(class_exists("Application", true));
        $this->assertTrue(class_exists("Controller", true));
        $this->assertTrue(class_exists("EventManager", true));
        $this->assertTrue(class_exists("Layout", true));
        $this->assertTrue(class_exists("Route", true));
        $this->assertTrue(class_exists("View", true));
    }
    
    public function testRegisterAutoloader()
    {
        set_include_path(
            implode(
                PATH_SEPARATOR, array(__DIR__ . '/classes', get_include_path())
            )
        );
        Loader::register();

        $this->assertTrue(class_exists("ns\Clazz", true));
        $this->assertTrue(class_exists("pr_Clazz", true));
    }
}