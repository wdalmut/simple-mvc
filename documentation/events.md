# Events

Events

 * `loop.startup`
 * `loop.shutdown`
 * `pre.dispatch`
 * `post.dispatch`
 
## Hooks

The `loop.startup` and `loop.shutdown` is called once at the start and at the
end of the simple-mvc workflow.

The `pre.dispatch` and `post.dispatch` is called for every controlled pushed 
onto the stack (use the `then()` method).

### Hooks params

The `loop.startup` and the `loop.shutdown` have the `Application` object as 
first parameter.

The `pre.dispatch` and `post.dispatch` have the `Router` object as first 
parameter.

The `pre.dispatch` has the `application` object that is useful if you want
to interact with the layout or other bootstrapped resources.

 * The router object is useful for modify the application flow.
 
```php
<?php
$app->getEventManager()->subscribe("pre.dispatch", function($router, $app) {
    // Use a real and better auth system
    if ($_SESSION["auth"] !== true) {
        $route["controller"] = "admin";
        $route["action"] = "login";
        
        $app->getBootstrap("layout")->setScriptName("admin.phtml");
    }
});
```

## Create new events

```php
<?php
// Call the hook named "my.hook" and pass the app as first arg.
$app->getEventManager()->publish("my.hook", array($app));
```

You can use the self-created hook using

```php
<?php
$app->getEventManager()->subscribe("my.hook", function($app) {/*The body*/});
```
