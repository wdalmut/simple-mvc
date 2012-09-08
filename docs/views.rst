Views
=====

The framework starts without view system. For add view support
you have to add `view` at bootstrap.

.. code-block:: php
    :linenos:

    <?php

    $app = new Application();
    $app->bootstrap('view', function(){
        $view = new View();
        $view->addViewPath(__DIR__ . '/../views');

        return $view;
    });

    $app->run();

The framework append automatically to a controller the right
view using controller and action name. Tipically you have to
create a folder tree like this: ::

    site
     + public
     + controllers
     - views
       - index
         - index.phtml

In this way the system load correctly the controller path and the view
script.

Layout support
--------------

The layout is handled as a simple view that wrap the controller view.

You need to bootstrap it. The normal layout name is "layout.phtml"

.. code-block:: php
    :linenos:

    <?php

    $app->bootstrap('layout', function(){
        $layout = new Layout();
        $layout->addViewPath(__DIR__ . '/../layouts');

        return $layout;
    });

You can change the layout script name using the setter.

.. code-block:: php
    :linenos:

    <?php
    $layout->setScriptName("base.phtml");

View Helpers
------------

If you want to create view helpers during your view bootstrap
add an helper closure.

.. code-block:: php
    :linenos:

    <?php
    $app->bootstrap('view', function(){
        $view = new View();
        $view->addViewPath(__DIR__ . '/../views');

        $view->addHelper("now", function(){
            return date("d-m-Y");
        });

        return $view;
    });

You can use it into you view as:

.. code-block:: php
    :linenos:

    <?php echo $this->now()?>

You can create helpers with many variables

.. code-block:: php
    :linenos:

    <?php
    $view->addHelper("sayHello", function($name){
        return "Hello {$name}";
    });

View system is based using the prototype pattern all of your
helpers attached at bootstrap time existing into all of your
real views.

Share view helpers
~~~~~~~~~~~~~~~~~~

View helpers are automatically shared with layout. In this way
you can creates global helpers during the bootstrap and interact with
those helpers at action time.

Pay attention that those helpers are copied. Use `static` scope for
share variables.

.. code-block:: php
    :linenos:

    <?php
    $app->bootstrap("layout", function(){
        $layout = new Layout();
        $layout->addViewPath(__DIR__ . '/../layouts');


        return $layout;
    });

    $app->bootstrap("view", function(){
        $view = new View();
        $view->addViewPath(__DIR__ . '/../views');

        $view->addHelper("title", function($part = false){
            static $parts = array();
            static $delimiter = ' :: ';

            return ($part === false) ? "<title>".implode($delimiter, $parts)."</title>" : $parts[] = $part;
        });

        return $view;
    });

From a view you can call the `title()` helper and it appends parts of you
page title.

Escapes
-------

Escape is a default view helper. You can escape variables using the
`escape()` view helper.

.. code-block:: php
    :linenos:

    <?php
    $this->escape("Ciao -->"); // Ciao --&gt;

Partials view
-------------

Partials view are useful for render section of your view separately. In
`simple-mvc` partials are view helpers.

.. code-block:: html
    :linenos:
    
    <!-- ctr/act.phtml -->
    <div>
        <div>
            <?php echo $this->partial("/path/to/view.phtml", array('title' => $this->title));?>
        </div>
    </div>

The partial view `/path/to/view.phtml` are located at `view` path.

.. code-block:: php
    :linenos:
    
    <!-- /path/to/view.phtml -->
    <p><?php echo $this->title; ?></p>

Multiple view scripts paths
---------------------------

`simple-mvc` support multiple views scripts paths. In other words you can specify
a single mount point `/path/to/views` after that you can add anther views script path,
this mean that the `simple-mvc` search for a view previously into the second views path
and if it is missing looks for that into the first paths. View paths are threated as
a stack, the latest pushed is the first used.

During your bootstrap add more view paths

.. code-block:: php
    :linenos:

    $app->bootstrap('view', function(){
        $view = new View();
        $view->addViewPath(__DIR__ . '/../views');
        $view->addViewPath(__DIR__ . '/../views-rewrite');

        return $view;
    });

If you have a view named `name.phtml` into `views` folder and now you create the view
named `name.phtml` into `views-rewrite` this one is used instead the original file in
`views` folder.

Partials and multiple view scripts paths
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

***Partial views follow the rewrite path strategy***. If you add the partial
view into a rewrite view folder, this view script is choosen instead
the original partial script.

.. code-block:: php
    :linenos:

    <?php echo $this->partial("my-helper.phtml", array('ciao' => 'hello'))?>

If `my-helper.phtml` is found in a rewrite point this view is used instead
the original view script.

The end.
