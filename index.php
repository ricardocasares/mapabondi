<?php 

require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
	'templates.path' => 'views'
));
// main route
$app->get('/', function () use ($app) {
	$app->render('layout.php',array());
});

$app->run();

?>