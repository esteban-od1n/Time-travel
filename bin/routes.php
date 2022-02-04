<?php
print_r($argv);

switch($argv[1] ?? "") {
	/*
	 * Rebuild cache and save the new routes
	 * */
	case "rebuild":
	case "r":
		// Open the cache dataabse
		$cache_db = new SQLite3(__DIR__ . "/../cache.db");
		$cache_db->exec("DROP TABLE IF EXISTS routes") or die("Cannot drop routes table"); // Drop the routes cache tablr
		$cache_db->exec("CREATE TABLE routes ( 
			uuid TEXT NOT NULL UNIQUE,
			path TEXT NOT NULL,
			controller TEXT NOT NULL,
			function TEXT NOT NULL,
			title TEXT
		)") or die("Cannot create routes table"); // And create the routing table

		// Prepare the insert statement
		$insert_statement = $cache_db->prepare("INSERT INTO routes(uuid, path, controller, function, title) VALUES (?,?,?,?,?)") or die("Cannot prepare the statement for inserting the routes");
		// Read the routing file
		$routes = yaml_parse_file(__DIR__ . "/../routing.yml") or die("Could not parse the yml file");
		// And insert the routes definition inside the routes table
		foreach($routes as $id => $route) {
			echo "Inserting route $id..." . PHP_EOL;
			$route_path = md5($route['path']);
			$route_controller = explode("::", $route['controller']);
			$insert_statement->bindValue(1, $id, SQLITE3_TEXT) or die("Could not insert the route uuid");
			$insert_statement->bindValue(2, $route_path, SQLITE3_TEXT) or die("Could not insert the route path");
			$insert_statement->bindValue(3, $route_controller[0], SQLITE3_TEXT) or die("Could not insert the route controller");
			$insert_statement->bindValue(4, $route_controller[1], SQLITE3_TEXT) or die("Could not insert the route controller function");
			$insert_statement->bindValue(5, $route['title'] ?? null, SQLITE3_TEXT) or die("Could not insert the route controller title");
			$insert_statement->execute() or die("Error inserting the route $id");
		}
		$cache_db->close();
		echo "Finished rebuilding the routing cache" . PHP_EOL;
		break;
	default:
		echo "Option $argv[1] does not exists";
		break;
}