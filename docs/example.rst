Examples of usage
=================

A simple base app execution

.. code-block:: php
    :linenos:

    <?php
    $app = new Application();

    $app->run();

Execute with bootstrap
----------------------

.. code-block:: php
    :linenos:
    
    <?php
    $app = new Application();

    $app->bootstrap("say-hello", function(){
        return array('example' => 'ciao');
    });

    $app->run();

Into a controller

.. code-block:: php
    :linenos:

    <?php
    class IndexController extends Controller
    {
        public function indexAction()
        {
            $element = $this->getResource('example');
        
            echo $element["example"];
        }
    }

Controller Forward
------------------

You can pass to another controller using `then()`

.. code-block:: php
    :linenos:

    <?php
    class IndexController extends Controller
    {
        public function indexAction()
        {
            // Add forward action
            $this->then("/index/forward");
        }
    
        public function forwardAction()
        {
            // append to index or use it directly
        }
    }

See `example` folder for a complete working example.

