# PHAR Archive

You can compile the `simple-mvc` phar archive. First of all
download dependencies using Composer.

```shell
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```

Now you can run the compile operation

```shell
$ ./compile
```

A `simple-mvc.phar` file will be created. Use it into your apps.

```php
<?php

require_once '/path/to/simple-mvc.phar';

$app = new Application();
// ...
```

The `phar` archive runs the autoloder (classmap strategy).