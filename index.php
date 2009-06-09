<?php

$ts = microtime(true);

/**
 * Itsy Bitsy Gallery
 *
 * @author Indranil Dasgupta
 * @version 0.4.2
 * @copyright Indranil Dasgupta, 27 December, 2008
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
		echo mime_content_type($file);
		for($j=0;$j<count($types);$j++) {
			if($file === $type[$j]) {
				$flag = 1;
			}
		}
		if($flag === 0) {
			$files[$i] = $file;
			$i++;
		}
	}
	
	
	
} else {
	die('Invalid directory. Pain!');
}

echo '<br>';
echo microtime(true) - $ts;

?>