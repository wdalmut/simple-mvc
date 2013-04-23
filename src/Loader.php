<?php
class Loader
{
    public static function register()
    {
        spl_autoload_register(function($className)
        {
            $className = ltrim($className, '\\');
            $fileName  = '';
            $namespace = '';
            if ($lastNsPos = strripos($className, '\\')) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

            if (!file_exists($fileName)) {
                throw new \RuntimeException("Unable to load class {$className} in file {$fileName}");
            }

            require $fileName;
        });
    }

    public static function classmap()
    {
        require_once __DIR__ . '/Bootstrap.php';
        require_once __DIR__ . '/Request.php';
        require_once __DIR__ . '/Router.php';
        require_once __DIR__ . '/Controller.php';
        require_once __DIR__ . '/EventManager.php';
        require_once __DIR__ . '/Layout.php';
        require_once __DIR__ . '/Route.php';
        require_once __DIR__ . '/View.php';
        require_once __DIR__ . '/Dispatcher.php';
        require_once __DIR__ . '/Application.php';
    }
}
