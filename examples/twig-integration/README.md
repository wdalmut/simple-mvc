# Twig Integration

First of all using composer for download all dependencies

```shell
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar update
```

A `vendor` folder will be created with `simple-mvc` and `twig` template engine.

Now you are ready to see the twig integration. The adapter `SimpleTwigView`
force the view engine to use `twig` instead the original one.