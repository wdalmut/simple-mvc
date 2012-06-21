# Events

Events

 * `loop.startup`
 * `loop.shutdown`
 * `pre.dispatch`
 * `post.dispatch`

## Examples

```php
<?php
$app->getEventManager()->subscribe("loop.startup", function(){
    echo "OKs.";
});
```

### Pre dispatch

 * Args
   * The router

Example
 
```php
<?php
$app->getEventManager()->subscribe("pre.dispatch", function($route){

    // Check your login...

    $route["controller"] = "admin";
    $route["action"] = "login";
});
```

