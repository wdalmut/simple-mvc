<?php 
class ErrorController extends Controller
{
    public function errorAction()
    {
        echo "--> error action <--";
        $this->setNoRender();
    }
}