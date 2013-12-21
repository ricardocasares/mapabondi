<?php 

/**
 * Mapabondi
 * @author rcasares
 **/

// require composer autoload
require 'vendor/autoload.php';

/**
 * CONFIGURATION
 **/

// slim instance
$app = new \Slim\Slim(array(
	'templates.path' => 'views',
	'mode' => isset($_SERVER['SLIM_MODE']) ? $_SERVER['SLIM_MODE'] : 'development'
));

// set production configuration
$app->configureMode('production', function () use ($app) {
		$services_json = json_decode(getenv("VCAP_SERVICES"),true);
		$mysql = $services_json["mysql-5.1"][0]["credentials"];
    $app->config(array(
        'log.enable' => true,
        'debug' => false,
        'dbhost' => $mysql['hostname'],
        'dbname' => $mysql['name'],
        'dbuser' => $mysql['username'],
        'dbpass' => $mysql['password']
    ));
});

// set development configuration
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'dbhost' => 'localhost',
        'dbname' => 'mapabondi',
        'dbuser' => 'root',
        'dbpass' => '1234'
    ));
});

/**
 * ROUTES
 **/

// REST API routes
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
	// get line routes
	$app->get('/find', function() use ($app) {
		findLinesByCoordinates($app);
	});
});

// catch all route
$app->get('.+', function () use ($app) {
	$app->render('layout.php',array());
});

// get all transports
function getTransports($app) {
	$app->response->headers->set('Content-Type', 'application/json');
	$sql = "SELECT * FROM transports  ORDER BY name";
	try {
		$db = getConnection($app);
		$sth = $db->query($sql);
		$transports = $sth->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"transports": ' . json_encode($transports) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"msg":'. $e->getMessage() .'}}';
	}
}

// get transport by id
function getTransportById($transport, $app) {
	$app->response->headers->set('Content-Type', 'application/json');
	$sql = "SELECT * FROM transports WHERE id = :transport";
	try {
		$db = getConnection($app);
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

// get all transport lines
function getTransportLines($transport, $app) {
	$app->response->headers->set('Content-Type', 'application/json');
	$sql = "SELECT * FROM `lines` WHERE transport_id = :transport ORDER BY name";
	try {
		$db = getConnection($app);
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

// get line routes
function getLineRoutes($line, $app) {
	$app->response->headers->set('Content-Type', 'application/json');
	$sql = "SELECT * FROM `routes` WHERE line_id = :line";
	try {
		$db = getConnection($app);
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

// finds lines by origin/destination coordinates
function findLinesByCoordinates($app) {
	$start = explode(',', $app->request->get('start'));
	$end   = explode(',', $app->request->get('end'));
	$app->response->headers->set('Content-Type', 'application/json');
	try {

		$sql = "SELECT DISTINCT(`lines`.id),`lines`.transport_id,`lines`.name FROM (SELECT line_id, lat, lng,
						(6378.10 * acos(cos(radians(:latStart)) * cos(radians( lat ))	* cos(radians(lng) - radians(:lngStart)) + sin(radians(:latStart))
						* sin(radians(lat)))) AS distance
						FROM routes
						HAVING distance < 0.5
						ORDER BY distance ASC) AS origin 
					JOIN (SELECT line_id, lat, lng,
						(6378.10 * acos(cos(radians(:latEnd)) * cos(radians( lat ))	* cos(radians(lng) - radians(:lngEnd)) + sin(radians(:latEnd))
						* sin(radians(lat)))) AS distance
						FROM routes
						HAVING distance < 0.5
						ORDER BY distance ASC) AS dst ON dst.line_id = origin.line_id
					JOIN `lines` ON `lines`.id = `dst`.line_id";

		$db = getConnection($app);
		$sth = $db->prepare($sql);
		$sth->bindParam('latStart',$start[0]);
		$sth->bindParam('lngStart',$start[1]);
		$sth->bindParam('latEnd',$end[0]);
		$sth->bindParam('lngEnd',$end[1]);
		$sth->execute();
		$lines = $sth->fetchAll(PDO::FETCH_ASSOC);

		echo '{"lines": ' . json_encode($lines) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"msg":'. $e->getMessage() .'}}';
	}
}

// hey ho, let's go!
$app->run();

// database connection configuration
function getConnection($app) {
	// get database config
	$dbhost = $app->config('dbhost');
	$dbuser = $app->config('dbuser');
	$dbpass = $app->config('dbpass');
	$dbname = $app->config('dbname');
	// set db handler
	$dbh    = new PDO("mysql:host=$dbhost;dbname=$dbname", $app->config('dbuser'), $app->config('dbpass'));
	// return db handler
	return $dbh;
}

?>