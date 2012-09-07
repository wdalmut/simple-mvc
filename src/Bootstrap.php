<?php
class Bootstrap
{
    private $_bootstrap = array();

    public function addResource($name, $hook)
    {
        if (!is_callable($hook)) {
            throw new RuntimeException("Hook must be callable");
        }

        $this->_bootstrap[$name] = $hook;
    }

    public function getResource($name)
    {
        if (array_key_exists($name, $this->_bootstrap)) {
            $b = $this->_bootstrap[$name];

            if (is_callable($b)) {
                $this->_bootstrap[$name] = call_user_func($b);
            }

            return $this->_bootstrap[$name];
        } else {
            return false;
        }
    }

    public function testMissingBootstrapResource()
    {
        $this->assertSame(false, $this->object->getResource("missing"));
    }
}
