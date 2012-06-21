<?php 
require_once realpath(__DIR__ . '/../../src/Loader.php');
Loader::register();

$app = new Application();
$app->setEventManager(new EventManager());

$app->bootstrap("view", function(){
    $view = new View();
    $view->setViewPath(__DIR__ . '/../views');
    
    return $view;
});

$app->bootstrap("layout", function(){
    $layout = new Layout();
    $layout->setViewPath(__DIR__ . '/../layouts');
    
    return $layout;
});

$app->run();