<?php

$ts = microtime(true);

/**
 * Itsy Bitsy Gallery
 * ==================
 * @author Indranil Dasgupta
 * @version 0.4.2
 * @copyright Indranil Dasgupta, 2009
 * @package default
 **/

if(version_compare(PHP_VERSION,'5','<')) die('Sorry, we require PHP 5+ to properly function!');


// Let's see where we are.
$path = @realpath(dirname('__FILE__'));

$types = array('image/bmp','image/gif','image/x-icon','image/jpeg','image/pjpeg','image/x-jps','image/x-portable-bitmap','image/x-pict','image/x-pcx','image/pict','image/png','image/tiff','image/x-tiff');

$files = array();

// We're scanning the directory now
if($dir_handle = @scandir($path)) {
	$i = 0;
	foreach($dir_handle as $file) {
		$flag = 0;
		// We're skipping the files that're part of this installation ;)
		if($file == 'index.php' || $file == 'README.textile' || $file == '.git' || $file == '.' || $file == '..' || $file == '.DS_Store' || $file == 'Thumbs.db' || $file == '.gitignore') {
			continue;
		}
		$file_info = getimagesize($file);
		if(in_array($file_info['mime'], $types)) {
			$flag = 1;
		}
		if($flag === 1) {
			$files[$i] = $file;
			$i++;
		}
	}
	// We can now work with the files in our $files counter. :D dandy, no?
	echo '<pre>';
	print_r($files);
	echo '</pre>';
		
} else {
	die('Invalid directory. Pain!');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Itsy Bitsy Gallery</title>
</head>
<body>
	
</body>
</html>
<?php
echo '<br>';
echo microtime(true) - $ts;
?>