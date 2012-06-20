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
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath('/path/to/controllers'),
            realpath('/path/to/src')
        )
    )        
);

$app = new Application();
$app->run();
```

The controller `IndexController.php` file should be like this

```php
<?php
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
