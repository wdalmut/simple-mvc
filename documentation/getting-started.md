# Getting Started

The goal is realize a web application in few steps.

See scripts into `example` for a real example. The base is create 
a public folder where your web server dispatch the `index.php`.

Create out from this folder the `controllers` path or whatever you
want (eg. `ctrs`).

```
 - controllers
   - IndexController.php
 - public
   - .htaccess
   - index.php
```

In practice you are ready. See the `.htaccess`

```
RewriteEngine  On
RewriteCond  %{REQUEST_FILENAME}  -s  [OR]
RewriteCond  %{REQUEST_FILENAME}  -l  [OR]
RewriteCond  %{REQUEST_FILENAME}  -d
RewriteRule  ^.*$  -  [NC,L]
RewriteRule  ^.*$  index.php  [NC,L]
```

The `index.php` is the main app entry point

```php
<?php 
set_include_path(realpath('/path/to/src'));

require_once 'Loader.php';
Loader::register();

$app = new Application();
$app->setControllerPath(__DIR__ . '/../controllers');
$app->run();
```

The controller `IndexController.php` file should be like this

```php
<?php 
class IndexController extends Controller
{
    public function indexAction()
    {
        echo "hello";
    }
}
```

See "view" doc for enable views supports.

The end.

## Urls with dashes

If you use a dash into an URL the framework creates the camel case
representation with different strategies if it is an action or a
controller.

```
/the-controller-name/the-action-name
```

Will be

```php
<?php
// the-controller-name => TheControllerName
class TheControllerName extends Controller
{
    public function theActionNameAction()
    {
        //the-action-name => theActionName
    }
}
```
