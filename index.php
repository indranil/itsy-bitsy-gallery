<?php
/**
 * Itsy Bitsy Gallery
 * ==================
 * @author Indranil Dasgupta
 * @version 0.5.0
 * @copyright Indranil Dasgupta, 2009
 * @package default
 **/

/*  CONFIGURATION -----
    ===================
    * The following
    * variables should
    * be configured.
    *
*/

$config['site_title']   =   'Itsy Bitsy Gallery';       // The title of the site.
$config['heading']      =   'Itsy Bitsy Gallery';       // The header of the page.
$config['min_width']    =   '400';                      // The minimum width for the thumbnails
$config['min_height']   =   '400';                      // The minimum height for the thumbnails
$config['copy_holder']  =   'Photographer';             // The person whose name comes as copyright holder
$config['copy_year']    =   '2009-2011';                // The years copyrighted for
$config['thumb_page']   =   8;                          // The number of thumbnails per page.


/* End Configuration.
    From here starts the hard stuff!
    Don't edit unless you know what you're doing.
*/

/*
 * For the intrigued, here's what we do:
 * 1) See the request. It can be an image, a page, or the index.
 * 2) Go through all the images inside the folder
 * 3) Only keep those that're images
 * 4) Get the number of images in the folder
 * 5) Display the images
 * 6) Profit(?)
 */

if(!isset($config['thumb_page'])) {
    $config['thumb_page'] = 8;
}

if(version_compare(PHP_VERSION,'5','<')) die('Sorry, we require PHP 5+ to properly function!');

if(isset($_GET['img']) && isset($_GET['pg'])) {
    $_GET['pg'] = '';
}

// Let's see where we are.
$path = @realpath(dirname('__FILE__'));

// These are the mime-types of the images we'll be checking against.
$types = array(
    'image/bmp',
    'image/gif',
    'image/x-icon',
    'image/jpeg',
    'image/pjpeg',
    'image/x-jps',
    'image/x-portable-bitmap',
    'image/x-pict',
    'image/x-pcx',
    'image/pict',
    'image/png',
    'image/tiff',
    'image/x-tiff',
);

// Checking if image or listing
$flag = 0;
// First, let's check if we're asking for a specific image or a listing...
if (isset($_GET['img']) && $_GET['img'] != '') {
    $file = $_GET['img'];
    if(file_exists($path.DIRECTORY_SEPARATOR.$file)) {
        $flag = 1;
    } else {
        // Error out. Image not found.

    }
} else {
    // This is not a specific image. Let's go through the folder and get our lists ready...
    $files = array();
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

    } else {
        // Error out. Not a valid directory.

    }
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
        width: 450px;
        height: 450px;
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
    
    .pagination {
        text-align: center;
        font-size: 12px;
    }
    
    .pagination a:hover {
        background: none !important;
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
    <?php /* Pagination is weirdly hard! Need to "class"-ify it by version 1.0 */ ?>
    <?php if($num_files > $config['thumb_page']) {
        if(isset($_GET['pg']) && $_GET['pg'] != '') {
            $pg_num = (int) $_GET['pg'];
            if($pg_num === 0) $pg_num = 1;
            $pg_thumb_start = (($pg_num - 1) * $config['thumb_page']);
            if(($pg_num * $config['thumb_page']) > $num_files) {
                $pg_thumb_end = $num_files -1;
            } else {
                $pg_thumb_end = ($pg_thumb_start + $config['thumb_page']) -1;
            }
        } else {
            $pg_num = 1;
            $pg_thumb_start = 0;
            $pg_thumb_end = $config['thumb_page'] - 1;
        }
    } else {
        if($num_files === 0) {
            $pg_num = 0;
            $pg_thumb_start = 0;
            $pg_thumb_end = -1;
        } else {
            $pg_num = 1;
            $pg_thumb_start = 0;
            $pg_thumb_end = $num_files-1;
        }
    } ?>
    
    <ul class="gallery">
        <?php if($num_files === 0) echo 'No images in folder!';
        for($i=$pg_thumb_start;$i<=$pg_thumb_end;$i++) {
            $imgfile = $files[$i];
            echo '<li><a href="?img=' . $imgfile['name'] . '"><img src="' . $imgfile['name'] . '" width="' . $imgfile['thumb_width'] . '" height="' . $imgfile['thumb_height'] . '" alt="image" /></a></li>' . "\n";
        } ?>
    </ul>
    <?php $num_pages = ceil($num_files / $config['thumb_page']); ?>
    <?php if($pg_num != 0) { ?>
    <p class="pagination">
        <?php if($pg_num != 1) { ?><a href="<?php echo '?pg=' . ($pg_num - 1); ?>">&laquo; Previous</a><?php } ?>
        Displaying page <?php echo $pg_num . ' of ' . $num_pages; ?>
        <?php if($pg_num < $num_pages) { ?><a href="<?php echo '?pg=' . ($pg_num + 1); ?>">Next &raquo;</a><?php } ?>
    </p>
    <?php } ?>
    
    <?php } // see line #226 ?>
    
    <p class="footer">
        Copyright &copy; <?php echo $config['copy_year'] . ', ' . $config['copy_holder']; ?>. All Rights Reserved. Powered by <a href="https://github.com/indranil/itsy-bitsy-gallery/" target="_blank">Itsy Bitsy Gallery</a>.
    </p>
</div>
</body>
</html>
<?php
/* that should be all. :) thanks for using Itsy Bitsy Gallery */
?>
