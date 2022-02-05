<?php
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

// Import all the Entities, Services and core files
dir_import("src/Entities");
dir_import("src/Services");
dir_import("src");

$app = new Core;

echo "<pre>";
print_r($_SERVER);
print_r(get_included_files());