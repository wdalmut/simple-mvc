<?php 
class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->hello = "hello...";
        
        $this->view->texts = new stdClass();
        $this->view->texts->para = "paragraph";
    }
}