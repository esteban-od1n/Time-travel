<?php
require_once '../core/core.php';
require_once '../core/template.php';
require_once '../core/storage.php';
require_once '../core/files.php';

/* 
 * Helper function to import all the php files inside a directory
 * */
function dir_import(string $path, int $depth = 0) {
	$dir = opendir($path);
	// Remove the . and .. directory
	readdir($dir); readdir($dir);
	// Read the files inside the directory
	while($file = readdir($dir)) {
		$full_path = "$path/$file";
		// If the specified path is a file import the file
		// else then import the files in that directory
		if(is_file($full_path))
			include $full_path;
		elseif($depth > 0) // The file is a directory 
			dir_import($full_path, $depth - 1);
	}
}

// Autoload all unregistered classes within the source directory
spl_autoload_register(function(string $entity) {
	$entity_dir = './src';
	$entity_parts = explode('\\', $entity);
	if(count($entity_parts) < 2)
		return false;
	$entity_dir .= "/$entity_parts[0]/$entity_parts[1].php";
	include_once $entity_dir;
	return true;
});

// Import all the files inside the source dir
// dir_import("src/Controllers");

echo "<pre>";
print_r($_SERVER);
print_r(get_included_files());