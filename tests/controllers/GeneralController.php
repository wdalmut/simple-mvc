<?php
class GeneralController extends Controller
{
    public function titleHelperAction()
    {

    }
    public function disableLayoutAction()
    {
        $this->disableLayout();
    }

    public function pullAction()
    {
        return array('title' => 'ok');
    }

    public function directAction()
    {

    }

    public function pullDataAction()
    {
        $clazz = new stdClass();

        $clazz->title = 'Controller Data';

        return $clazz;
    }

    public function aAction()
    {
        $this->view->b = "B";
        $this->setRenderer("/general/b");
    }

    public function cAction()
    {
        $this->view->c = "C";
    }

    public function outAction()
    {
        echo "opssssss!";
        return "ret from out";
    }
}
