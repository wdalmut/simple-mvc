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
