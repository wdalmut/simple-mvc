<?php 
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(__DIR__ . '/../controllers'),
            realpath(__DIR__ . '/../../src'),
            get_include_path()
        )
    )        
);

require_once 'View.php';
require_once 'Route.php';
require_once 'Application.php';
require_once 'Controller.php';

$app = new Application();

$app->bootstrap("view", function(){
    $view = new View();
    $view->setViewPath(__DIR__ . '/../views');
    
    return $view;
});

$app->run();