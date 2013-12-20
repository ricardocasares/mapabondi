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
	// get line routes
	$app->get('/find', function() use ($app) {
		findLinesByCoordinates($app);
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

function findLinesByCoordinates($app) {
	$start = $app->request->params('start');
	$end   = $app->request->params('end');
	$app->response->headers->set('Content-Type', 'application/json');
	try {
		$lines = getLinesMatching($start,$end);

		echo '{"lines": ' . json_encode($lines,JSON_UNESCAPED_UNICODE) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"msg":'. $e->getMessage() .'}}';
	}
}

function getLinesMatching($start,$end)
{
	$start = haversine($start);
	$end = haversine($end);
	
	if(!$start OR !$end) return FALSE;

	$result = array_merge($start,$end);

	return array_map("unserialize", array_unique(array_map("serialize", $result)));
}

function haversine($point)
{
	$point = explode(',',$point);

	$sql = "SELECT `lines`.name, `lines`.id FROM `lines`
				JOIN (SELECT line_id, lat, lng,
				(6378.10 * acos(cos(radians(:lat)) * cos(radians( lat ))	* cos(radians(lng) - radians(:lng)) + sin(radians(:lat))
				* sin(radians(lat)))) AS distance
				FROM routes
				HAVING distance < 0.2 ORDER BY distance) AS matches ON matches.line_id = `lines`.id
				GROUP BY line_id
				ORDER BY distance";

	$db = getConnection();
	$sth = $db->prepare($sql);
	$sth->bindParam('lat',$point[0]);
	$sth->bindParam('lng',$point[1]);
	$sth->execute();
	$lines = $sth->fetchAll(PDO::FETCH_ASSOC);
	return $lines;
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
	return $dbh;
}

class MyPDOStatement extends PDOStatement
{
  protected $_debugValues = null;

  protected function __construct()
  {
    // need this empty construct()!
  }

  public function execute($values=array())
  {
    $this->_debugValues = $values;
    try {
      $t = parent::execute($values);
      // maybe do some logging here?
    } catch (PDOException $e) {
      // maybe do some logging here?
      throw $e;
    }

    return $t;
  }

  public function _debugQuery($replaced=true)
  {
    $q = $this->queryString;

    if (!$replaced) {
      return $q;
    }

    return preg_replace_callback('/:([0-9a-z_]+)/i', array($this, '_debugReplace'), $q);
  }

  protected function _debugReplace($m)
  {
    $v = $this->_debugValues[$m[1]];
    if ($v === null) {
      return "NULL";
    }
    if (!is_numeric($v)) {
      $v = str_replace("'", "''", $v);
    }

    return "'". $v ."'";
  }
}

?>