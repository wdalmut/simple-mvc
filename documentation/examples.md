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
    }
}
```