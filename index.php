<?php 

require 'vendor/autoload.php';

// slim configuration
$app = new \Slim\Slim(array(
	'templates.path' => 'views'
	));

// main route
$app->get('/', function () use ($app) {
	$app->render('layout.php',array());
});

$app->group('/api', function() use ($app) {
	// get all transports
	$app->get('/transports', function() use ($app) {
		getTransports($app);
	});
	// get transport by id
	$app->get('/transports/:transport', function($transport) use ($app) {
		getTransportById($transport,$app);
	});
	// get all transport lines
	$app->get('/transports/:transport/lines', function($transport) use ($app) {
		getTransportLines($transport,$app);
	});
	// get line routes
	$app->get('/lines/:line/routes', function($line) use ($app) {
		getLineRoutes($line,$app);
	});
});

function getTransports($app) {
	$app->response->headers->set('Content-Type', 'application/json');
	$sql = "SELECT * FROM transports";
	try {
		$db = getConnection();
		$sth = $db->query($sql);
		$transports = $sth->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"transports": ' . json_encode($transports) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"msg":'. $e->getMessage() .'}}';
	}
}

function getTransportById($transport, $app) {
	$app->response->headers->set('Content-Type', 'application/json');
	$sql = "SELECT * FROM transports WHERE id = :transport";
	try {
		$db = getConnection();
		$sth = $db->prepare($sql);
		$sth->bindParam("transport", $transport);
		$sth->execute();
		$transport = $sth->fetchObject();
		$db = null;
		echo json_encode($transport);
	} catch(PDOException $e) {
		echo '{"error":{"msg":'. $e->getMessage() .'}}';
	}
}

function getTransportLines($transport, $app) {
	$app->response->headers->set('Content-Type', 'application/json');
	$sql = "SELECT * FROM `lines` WHERE transport_id = :transport";
	try {
		$db = getConnection();
		$sth = $db->prepare($sql);
		$sth->bindParam('transport',$transport);
		$sth->execute();
		$lines = $sth->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"lines": ' . json_encode($lines) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"msg":'. $e->getMessage() .'}}';
	}
}

function getLineRoutes($line, $app) {
	$app->response->headers->set('Content-Type', 'application/json');
	$sql = "SELECT * FROM `routes` WHERE line_id = :line";
	try {
		$db = getConnection();
		$sth = $db->prepare($sql);
		$sth->bindParam('line',$line);
		$sth->execute();
		$routes = $sth->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"routes": ' . json_encode($routes) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"msg":'. $e->getMessage() .'}}';
	}
}

// RUN!!
$app->run();

// database connection configuration
function getConnection() {
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="1234";
	$dbname="mapabondi";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
?>