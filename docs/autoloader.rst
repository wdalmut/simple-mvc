Autoloader
==========

`simple-mvc` provides two strategies for loading classes for itself
and only one strategy for autoload your classes.

Classmap
--------

The classmap loads only `simple-mvc` classes. If you have a self-designed
autoloader you have to use this strategy for reduce conflicts during
the autoloading process. ::

    <?php
    require_once '/path/to/simple/Loader.php';

    // Load all simple-mvc classes
    Loader::classmap();

PSR-0 Autoloader
----------------

If you want to use the PSR-0 autoloader you have to register the
autoloader. ::

    <?php
    require_once '/path/to/simple/Loader.php';

    set_include_path(
        implode(
            PATH_SEPARATOR,
            array(
                '/path/to/project',
                get_include_path()
            )
        )
    );

    // Load all simple-mvc classes
    Loader::register();

The autoloader loads automatically namespaced classes and prefixed.

Prefix example: ::

    <php
    // Prefix -> ClassName.php
    class Prefix_ClassName
    {

    }

Namespace example: ::

    <?php
    namespace Ns

    // Ns -> ClassName.php
    class ClassName
    {

    }


