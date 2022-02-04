<?php
/* 
 * Read all PHP file inside a directory
 * */
function dir_import(string $path, int $depth = 0) {
	$dir = opendir($path);
	// Remove the . and .. directory
	readdir($dir); readdir($dir);
	while($file = readdir($dir)) {
		$full_path = "$path/$file";
		if(is_file($full_path)) // The file is probably a php file
			include $full_path;
		elseif($depth > 0) // The file is a directory 
			dir_import($full_path, $depth - 1);
	}
}

// Import all the files inside the src directory
dir_import("../src/entities");
dir_import("../src/services");
dir_import("../src");

echo "<pre>";
print_r($_SERVER);
print_r(get_included_files());