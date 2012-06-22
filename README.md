# A simple MVC [VC] framework [![Build Status](https://secure.travis-ci.org/wdalmut/simple-mvc.png)](http://travis-ci.org/wdalmut/simple-mvc?branch=master)

A simple MVC framework heavly inspired to different PHP microframeworks and
PHP MVC framework like ZF1.

## Why?

I want to try out the test-driven development [at least write some tests ;)].

Just for my pleasure.

## Goals

 * PHPUnit
 * All class must be less than 100 lines of code
 
## Example

```php
<?php
class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->line = "A sentence";
    }
}
```

# The view

```php
<p><?php echo $this->line?></p>
```

All are orchestreated by `Application`

```php
<?php
$app = new Application();

$app->bootstrap("view", function(){
    $view = new View();
    $view->setViewPath(__DIR__ . '/views');
    return $view;
});

$app->run();
```

# The layout (Two step view)

```php
<?php
$app->bootstrap("layout", function(){
    $layout = new Layout();
    $layout->setViewPath(__DIR__ . '/layouts');
    return $layout;
});
```

The system use the `layout.phtml` name as default (change using setter).

```php
<html>
    <head><title><?php echo $this->title?></title></head>
    <body><?php echo $this->content?></body>
</html>
```

The system use the controller view as `content` property.

The end.