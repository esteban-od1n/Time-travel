<?php

if(count($argv) < 2) die("You should provide a option" .PHP_EOL);

// Open the database for transactions
$cache_db = new SQLite3(__DIR__ . "/../cache.db");

function routes_cache(SQLite3 $db, string $routing_file) {
	$db->exec("DROP TABLE IF EXISTS routes") or die("Cannot drop routes table");
	$db->exec("CREATE TABLE routes (
		uuid TEXT NOT NULL UNIQUE,
		path TEXT NOT NULL,
		controller TEXT NOT NULL,
		function TEXT NOT NULL,
		title TEXT
	)") or die("Cannot create routes table");

	// Prepare the insert statement
	$statement = $db->prepare("INSERT INTO routes(uuid, path, controller, function, title) VALUES (?,?,?,?,?)") or die("Cannot prepare the statement for inserting the routes");
	// Read the routing file
	$routes = yaml_parse_file(__DIR__ . "/../$routing_file") or die("Could not parse the yml file");
	// And insert the routes definition inside the routes table
	foreach($routes as $id => $route) {
		echo "Inserting route $id..." . PHP_EOL;
		$route_path = md5($route['path']);
		$controller = explode("::", $route['controller']);
		$title = $route['title'] ?? null;
		$statement->bindValue(1, $id, SQLITE3_TEXT) or die("Could not insert the route uuid");
		$statement->bindValue(2, $route_path, SQLITE3_TEXT) or die("Could not insert the route path");
		$statement->bindValue(3, $controller[0], SQLITE3_TEXT) or die("Could not insert the controller");
		$statement->bindValue(4, $controller[1], SQLITE3_TEXT) or die("Could not insert the controller function");
		$statement->bindValue(5, $title, SQLITE3_TEXT) or die("Could not insert the route controller title");
		$statement->execute() or die("Error inserting the route $id");
	}
	echo "Finished rebuilding the routing cache" . PHP_EOL;
}

switch($argv[1] ?? "") {
	case "rebuild":
		routes_cache($cache_db, "routing.yml");
		break;
	default:
		echo "Option $argv[1] does not exists";
		break;
}

$cache_db->close();
