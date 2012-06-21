<?php 
class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->hello = "hello";
        
        
        $this->then("/index/kindle");
    }
    
    public function kindleAction()
    {
        $this->view->cose = "ciao";
    }
}