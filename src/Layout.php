<?php 

require_once __DIR__ . '/View.php';

class Layout extends View
{
    public $content;
    
    protected $_scriptName = 'layout.phtml';
    
    public function setScriptName($name)
    {
        $this->_scriptName = $name;
    }
    
    public function getScriptName()
    {
        return $this->_scriptName;
    }
}