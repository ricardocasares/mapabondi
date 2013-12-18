<?php 

require 'vendor/autoload.php';

$app = new \Slim\Slim();
// main route
$app->get('/', function () {
	echo "Welcome to mapabondi";
});

$app->run();

?>