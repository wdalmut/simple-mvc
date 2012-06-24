<?php 
require_once realpath(__DIR__ . '/../../src/Loader.php');
Loader::register();

$app = new Application();

// By default but more clear
$app->setControllerPath(__DIR__ . '/../controllers');

$app->bootstrap("view", function(){
    $view = new View();
    $view->setViewPath(__DIR__ . '/../views');
    
    return $view;
});

$app->bootstrap("layout", function(){
    $layout = new Layout();
    $layout->setViewPath(__DIR__ . '/../layouts');
    
    $layout->addHelper("title", function($part = false){
        static $parts = array();
        static $delimiter = ' :: ';
    
        return ($part === false) ? implode($delimiter, $parts) : $parts[] = $part;
    });
    
    return $layout;
});

$app->run();