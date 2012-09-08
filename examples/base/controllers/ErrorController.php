<?php
class ErrorController extends Controller
{
    public function errorAction()
    {
        echo "<pre>";
        var_dump($this->getParams());

    }
}
