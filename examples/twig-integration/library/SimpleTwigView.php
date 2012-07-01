<?php
class SimpleTwigView extends View
{
    private $_twig;
    public $ext = "twig";
    
    public function initTwig()
    {
        $loader = new Twig_Loader_Filesystem($this->getViewPaths());
        $this->_twig = new Twig_Environment($loader);
    }
    
    public function render($filename, $data = false)
    {
        $template = $this->_twig->loadTemplate($filename);
        
        if (is_array($data)) {
            $data = array_merge($this->_getData(), $data);
        } else {
            $data = $this->_getData();
        }
        
        return $template->render($data);
    }
}
