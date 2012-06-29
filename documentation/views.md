# Views

The framework starts without view system. For add view support
you have to add `view` at bootstrap.

```php
<?php

$app = new Application();
$app->bootstrap('view', function(){
    $view = new View();
    $view->addViewPath(__DIR__ . '/../views');
    
    return $view;
});

$app->run();
```

The framework append automatically to a controller the right
view using controller and action name. Tipically you have to 
create a folder tree like this:

```
site
 + public
 + controllers
 - views
   - index
     - index.phtml
```

In this way the system load correctly the controller path and the view
script.

## Layout support

The layout is handled as a simple view that wrap the controller view.

You need to bootstrap it. The normal layout name is "layout.phtml"

```php
<?php

$app->bootstrap('layout', function(){
    $layout = new Layout();
    $layout->addViewPath(__DIR__ . '/../layouts');
    
    return $layout;
});

```

You can change the layout script name using the setter.

```php
<?php
$layout->setScriptName("base.phtml");
```

## View Helpers

If you want to create view helpers during your view bootstrap
add an helper closure.

```php
<?php
$app->bootstrap('view', function(){
    $view = new View();
    $view->addViewPath(__DIR__ . '/../views');
    
    $view->addHelper("now", function(){
        return date("d-m-Y");
    });
    
    return $view;
});
```

You can use it into you view as:

```php
<?php echo $this->now()?>
```

You can create helpers with many variables

```php
<?php
$view->addHelper("sayHello", function($name){
    return "Hello {$name}";
});
```

View system is based using the prototype pattern all of your 
helpers attached at bootstrap time existing into all of your
real views.

You can add view helpers into your controller but you can 
interact only with your dedicated and prototyped instance. The
helper doesn't exists into other views

```php
<?php
public function indexAction()
{
    // Only into this controller view!
    $this->view->addHelper("tmp", function(){return "tmp";});
}
```

### Layout helpers

Layout helpers are automatically shared with each views. In this way
you can creates global helpers during the bootstrap and interact with
those helpers at action time.

Pay attention that those helpers are copied. Use `static` scope for
share variables.

```php
<?php
$app->bootstrap("layout", function(){
    $layout = new Layout();
    $layout->addViewPath(__DIR__ . '/../layouts');
    
    $layout->addHelper("title", function($part = false){
        static $parts = array();
        static $delimiter = ' :: ';
    
        return ($part === false) ? "<title>".implode($delimiter, $parts)."</title>" : $parts[] = $part;
    });
    
    return $layout;
});
```

From a view you can call the `title()` helper and it appends parts of you
page title.

## Escapes

Escape is a default view helper. You can escape variables using the 
`escape()` view helper.

```php
<?php
$this->escape("Ciao -->"); // Ciao --&gt;
```

## Partials view

Partials view are useful for render section of your view separately. In
`simple-mvc` partials are view helpers.

```php
<!-- ctr/act.phtml -->
<div>
     <div>
          <?php echo $this->partial("/path/to/view.phtml", array('title' => $this->title));?>
     </div>
</div>
```

The partial view `/path/to/view.phtml` are located at `view` path.

```php
<!-- /path/to/view.phtml -->
<p><?php echo $this->title; ?></p>
```

## Multiple view scripts paths

`simple-mvc` support multiple views scripts paths. In other words you can specify
a single mount point `/path/to/views` after that you can add anther views script path,
this mean that the `simple-mvc` search for a view previously into the second views path
and if it is missing looks for that into the first paths. View paths are threated as 
a stack, the latest pushed is the first used.

During your bootstrap add more view paths

```
$app->bootstrap('view', function(){
    $view = new View();
    $view->addViewPath(__DIR__ . '/../views');
    $view->addViewPath(__DIR__ . '/../views-rewrited');
    
    return $view;
});
```

If you have a view named `name.phtml` into `views` folder and now you create the view
named `name.phtml` into `views-rewrited` this one is used instead the original file in 
`views` folder.

The end.