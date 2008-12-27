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

if(version_compare(PHP_VERSION,'5','<'))
	die('Sorry, we require PHP 5+ to properly function!');


// Let's see where we are.
define('PATH', @realpath(dirname('__FILE__')));

if($dir_handle = scandir(PATH)) {
	echo '<pre>';
	print_r($dir_handle);
	echo '</pre>';
	
	
	
} else {
	die('Invalid directory. Pain!');
}

echo '<br>';
echo microtime(true) - $ts;

?>