# A simple MVC [VC] framework

A simple ***push & pull MVC framework*** heavly inspired to different PHP microframeworks and
PHP MVC framework like ZF1.

## Why?

I want to try out the test-driven development [at least write some tests ;)].

Just for my pleasure.

## Goals

 * PHPUnit
 * Very simple implementation (***only 8 classes*** + autoloader)
 * PHP 5.3+ implementation

## Features

 * 100% MVC implementation [66% no model support] ;)
 * Useful hooks (Fixed events)
  * Loop Startup
  * Pre Dispatch
  * Init Hook
  * Post Dispatch
  * Loop Shutdown
 * View Renderer Switch
 * View Helpers
 * Partial views
 * Two step view (Layout support)
 * Controllers stack
 * Headers handler
 * Event manager (Self designed hooks)
 * Router
  * Only controller/action names
  * Dash URLs support (/a-dash/the-name-of-content)
 * Pull Driven requests
  * View request data to a controller-action
 * Rewritable views
  * Different views mount points for rewrite views

## Install with Composer

If you want you can use Composer for install simple-mvc.
Create the `composer.json`

```json
{
    "require": {
        "wdalmut/simple-mvc": "*"
    }
}
```

Now you can install the framework

```shell
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```

You can use the Composer autoloader

```php
<?php
require_once 'vendor/autoloader.php';

$app = new Application();
//...
```

## Examples and docs

 * [Documentation](documentation)
 * [Real Example](examples)

## Build Status

 * Master branch
   * [![Build Status](https://secure.travis-ci.org/wdalmut/simple-mvc.png?branch=master)](http://travis-ci.org/wdalmut/simple-mvc?branch=master)
 * Development branch
   * [![Build Status](https://secure.travis-ci.org/wdalmut/simple-mvc.png?branch=development)](http://travis-ci.org/wdalmut/simple-mvc?branch=development)

The end.
