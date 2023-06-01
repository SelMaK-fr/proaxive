<?php
// Défini le décalage horaire (pour la France)
//ini_set('display_errors',1);
date_default_timezone_set('Europe/Paris');

use Whoops\Run;

define('ROOT', dirname(__DIR__).'/');
#################
# REQUIRE
#################
require ROOT . '/app/App.php';
App::scanFolder();

require ROOT . '/vendor/autoload.php';
// Autoload SRC
App::load();
// Load file .ENV configuration
App::getEnv();


#################
# WOOPS ERROR
#################
if(App::getJson()->full_error == 1 or getenv('APP_DEBUG') == true)
{
    $whoops = new Run;
    $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}
# PHP Errors
if(App::getJson()->php_error == 1){
    ini_set('display_errors',1);
}

require ROOT . '/config/routing.php';