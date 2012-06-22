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

## Escapes

You can escape variables using the `escape()` method

```php
<?php
$this->escape("Ciao -->"); // Ciao --&gt;
```

The end.