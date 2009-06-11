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

/*	CONFIGURATION -----
	===================
	* The following
	* variables should
	* be configured.
	*
*/

$config['site_title']	=	'Itsy Bitsy Gallery';		// The title of the site.
$config['heading']		=	'Gallery';					// The header of the page.
$config['min_width']	=	'400';						// The minimum width for the thumbnails
$config['min_height']	=	'400';						// The minimum height for the thumbnails
$config['box_width']	=	'450';						// The width of the box containing the thumbnail
$config['box_height']	=	'450';						// The height of the box containing the thumbnail
$config['copy_holder']	=	'Photographer';				// The person whose name comes as copyright holder
$config['copy_year']	=	'2009-2010';				// The years copyrighted for


/* End Configuration.
	From here starts the hard stuff!
	Don't edit unless you know what you're doing.
*/

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
			$files[$i]['name'] = $file;
			foreach($file_info as $key => $value) {
				$files[$i][$key] = $value;
			}
			$i++;
		}
	}
	$num_files = count($files);
	// We can now work with the files in our $files counter. :D dandy, no?
		
} else {
	die('Invalid directory. Pain!');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $config['site_title']; ?></title>
</head>
<body>
	<h1><?php echo $config['heading']; ?></h1>
	<p><?php echo $num_files; ?> images contained within the folder.</p>
	<ul>
		<?php foreach($files as $imgfile) {
			if($imgfile[0] >= $imgfile[1]) {
				if($imgfile[0] > $config['min_width']) {
					$width = $config['min_width'];
					$height = $width * ($imgfile[1] / $imgfile[0]);
				} else {
					$width = $imgfile[0];
					$height = $imgfile[1];
				}
			} else {
				if($imgfile[1] > $config['min_height']) {
					$height = $config['min_height'];
					$width = $height * ($imgfile[0] / $imgfile[1]);
				} else {
					$height = $imgfile[1];
					$width = $imgfile[0];
				}
			}
			echo '<li><img src="' . $imgfile['name'] . '" width="' . $width . '" height="' . $height . '" alt="image" /></li>' . "\n";
		} ?>
	</ul>
	
	<div class="footer">
		Copyright &copy; <?php echo $config['copy_year'] . ', ' . $config['copy_holder']; ?>. All Rights Reserved. Powered by <a href="#">Itsy Bitsy Gallery</a>.
	</div>
</body>
</html>
<?php
echo '<br>';
echo microtime(true) - $ts;
?>