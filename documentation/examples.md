# Examples of usage

A simple base app execution

```php
<?php
$app = new Application();

$app->run();
```

## Execute with bootstrap

```php
<?php
$app = new Application();

$app->bootstrap("say-hello", function(){
    return array('example' => 'ciao');
});

$app->run();

```

Into a controller

```php
<?php
class IndexController extends Controller
{
    public function indexAction()
    {
        $element = $this->getResource('example');
        
        echo $element["example"];
    }
}
```

## Controller Forward

You can pass to another controller using `then()`

```php
<?php
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
        // appended to index or use it directly
    }
}
```