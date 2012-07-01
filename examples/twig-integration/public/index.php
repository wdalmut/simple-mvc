<?php 
require_once realpath(__DIR__ . '/../vendor/autoload.php');
require_once realpath(__DIR__ . '/../library/SimpleTwigView.php');

$app = new Application();

// By default but more clear
$app->setControllerPath(__DIR__ . '/../controllers');

$app->bootstrap("view", function(){
    $view = new SimpleTwigView();
    $view->addViewPath(realpath(__DIR__ . '/../views'));
    $view->setViewExt(".twig");
    $view->initTwig();
    
    return $view;
});

$app->run();
