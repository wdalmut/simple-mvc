<?php
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$app = new Application();

// By default but more clear
$app->setControllerPath(__DIR__ . '/../controllers');

$app->bootstrap("view", function(){
    $view = new TwigView();
    $view->addViewPath(realpath(__DIR__ . '/../layouts'));
    $view->addViewPath(realpath(__DIR__ . '/../views'));
    $view->setViewExt(".twig");
    $view->initTwig(__DIR__ . '/../views/cache');

    return $view;
});

$app->run();
