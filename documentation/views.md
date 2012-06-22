# Views

The framework starts without view system. For add view support
you have to add `view` at bootstrap.

```php
<?php

$app = new Application();
$app->bootstrap('view', function(){
    $view = new View();
    $view->setViewPath(__DIR__ . '/../views');
    
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
    $l = new Layout();
    $l->setViewPath(__DIR__ . '/../layouts');
    
    return $;
});

```

You can change the layout script name using the setter.

```php
<?php
$l->setScriptName("base.phtml");
```

## View Helpers

If you want to create view helpers during your view bootstrap
add an helper closure.

```php
<?php
$app->bootstrap('view', function(){
    $view = new View();
    $view->setViewPath(__DIR__ . '/../views');
    
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

## Escapes

Escape is a default view helper. You can escape variables using the 
`escape()` view helper.

```php
<?php
$this->escape("Ciao -->"); // Ciao --&gt;
```

The end.