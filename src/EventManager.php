<?php
class EventManager
{
    private $_listeners;

    public function subscribe($event, $listener)
    {
        if (!is_callable($listener)) {
            throw new RuntimeException("You must pass a callable!");
        }

        if (!isset($this->_listeners[$event])) {
            $this->_listeners[$event] = array();
        }

        $this->_listeners[$event][] = $listener;
    }

    public function publish($event, array $arguments = array())
    {
        if ($this->_listeners($event)) {
            foreach ($this->_listeners($event) as $listener) {
                call_user_func_array($listener, $arguments);
            }
        }
    }

    protected function _listeners($event)
    {
        if (
            $this->_listeners && is_array($this->_listeners) &&
            array_key_exists($event, $this->_listeners)
            ) {
            return $this->_listeners[$event];
        } else {
            return array();
        }
    }

    public function clear($event = null)
    {
        if ($event !== null) {
            unset($this->_listeners[$event]);
        } else {
            $this->_listeners = array();
        }
    }
}
