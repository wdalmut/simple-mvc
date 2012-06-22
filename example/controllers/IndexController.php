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
    
    public function xmlAction()
    {
        $this->setNoRender();
        $this->disableLayout();
        
        $dom = new DOMDocument("1.0", "UTF-8");
        $element = $dom->createElement("example", "walter");
        $dom->appendChild($element);
        
        echo $dom->saveXML();
        
        $this->addHeader("Content-Type", "text/xml");
    }
}