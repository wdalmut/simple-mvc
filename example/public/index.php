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

require_once 'Loader.php';
Loader::register();

$app = new Application();

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