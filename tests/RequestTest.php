<?php
require_once __DIR__ . '/../src/Request.php';

class RequestTest extends PHPUnit_Framework_TestCase
{
    public function testNewHttp()
    {
        $this->markTestSkipped("Need Selenium or PhantomJS...");
    }
}
