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
$config['heading']		=	'Itsy Bitsy Gallery';		// The header of the page.
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
	if(isset($_GET['img']) && $_GET['img'] != '') {
		$img_name = $_GET['img'];
		$flag = 0;
		foreach($dir_handle as $file) {
			if($file === $img_name) {
				$flag = 1;
				break;
			}
		}
		if($flag === 0) {
			$msg = "No such image found in folder";
		}
	} else {
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
				if($files[$i][0] >= $files[$i][1]) {
					if($files[$i][0] > $config['min_width']) {
						$files[$i]['thumb_width'] = $config['min_width'];
						$files[$i]['thumb_height'] = $files[$i]['thumb_width'] * ($files[$i][1] / $files[$i][0]);
					} else {
						$files[$i]['thumb_width'] = $files[$i][0];
						$files[$i]['thumb_height'] = $files[$i][1];
					}
				} else {
					if($files[$i][1] > $config['min_height']) {
						$files[$i]['thumb_height'] = $config['min_height'];
						$files[$i]['thumb_width'] = $files[$i]['thumb_height'] * ($files[$i][0] / $files[$i][1]);
					} else {
						$files[$i]['thumb_height'] = $files[$i][1];
						$files[$i]['thumb_width'] = $files[$i][0];
					}
				}
				$i++;
			}
		}
		$num_files = count($files);
		// We can now work with the files in our $files counter. :D dandy, no?
	}
		
} else {
	die('Invalid directory. Pain!');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $config['site_title']; ?></title>
	
	<style type="text/css" media="screen">
	<![CDATA[
	
	* {
		margin: 0;
		padding: 0;
	}
	
	a {
		color: #536661;
		text-decoration: underline;
		padding: 1px;
	}
	
	a:hover {
		color: #536661;
		background-color: #afa;
		text-decoration: none;
	}
	
	a img {
		border: none;
	}
	
	body {
		font-family: Helvetica, Arial, "Lucida Grande", Verdana, sans-serif;
		text-align: center;
		background-color: #A8AD90;
		color: #36362C;
	}
	
	.wrapper {
		text-align: left;
		width: 1000px;
		margin: 0 auto;
	}
	
	h1 {
		font: normal 35px/40px Georgia, "Times New Roman", serif;
		border-bottom: 1px dotted #536661;
	}
	
	h1 a {
		color: #36362C;
		text-decoration: none;
	}
	
	h1 a:hover {
		background: none;
		color: #36362C;
	}
	
	.imgcount {
		font-style: italic;
		font-size: 12px;
		margin-top: -10px;
		padding-bottom: 10px;
	}
	
	.gallery {
		list-style: none;
	}
	
	.gallery li {
		float: left;
		width: <?php echo $config['box_width']; ?>px;
		height: <?php echo $config['box_height']; ?>px;
		text-align: center;
		margin: 0 30px 30px 0;
	}
	
	.gallery li a img {
		padding: 2px;
		border: 2px solid #36362C;
	}
	
	.gallery li a:hover {
		background: none !important;
	}
	
	.gallery li a:hover img {
		border-color: #000;
	}
	
	.main_img {
		text-align: center;
	}
	
	.footer {
		clear: both;
		border-top: 1px dotted #536661;
		padding-top: 4px;
		font-size: 12px;
		padding: 10px;
	}
	
	]]>
	</style>
	
</head>
<body>
<div class="wrapper">
	<h1>
		<?php if(isset($_GET['img']) && $_GET['img'] != '') { ?>
			<a href="index.php" title="Back home"><?php echo $config['heading']; ?> &laquo;</a>
		<?php } else {
			echo $config['heading'];
		} ?>
	</h1>
	<?php if(isset($num_files) && $num_files != 0) { ?><p class="imgcount"><?php echo $num_files; ?> images contained within the folder.</p><?php } ?>
	<?php if(isset($_GET['img']) && $_GET['img'] != '') {
		if(isset($msg)) echo $msg;
		echo '<div class="main_img"><img src="' . $img_name . '" alt="image" /></div>'; 
	} else { ?>
	<ul class="gallery">
		<?php if(count($files) === 0) echo 'No images in folder!';
		foreach($files as $imgfile) {
			echo '<li><a href="?img=' . $imgfile['name'] . '"><img src="' . $imgfile['name'] . '" width="' . $imgfile['thumb_width'] . '" height="' . $imgfile['thumb_height'] . '" alt="image" /></a></li>' . "\n";
		} ?>
	</ul>
	<?php } ?>
	
	<p class="footer">
		Copyright &copy; <?php echo $config['copy_year'] . ', ' . $config['copy_holder']; ?>. All Rights Reserved. Powered by <a href="http://dawnstudios.com/gallery/">Itsy Bitsy Gallery</a>.
	</p>
</div>
</body>
</html>
<?php
/* that should be all. :) thanks for using Itsy Bitsy Gallery */
?>