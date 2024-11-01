<?php
function d_create_thumbnail($image_name,$galleryname){
	$save_to_file = true;
	$image_quality = 100;
	$image_type = -1;
	$cut_x = 0;
	$cut_y = 0;
	$max_x = 250;
	$max_y = 250;
	
	$plugin_url = plugins_url();
	$upload_dir = wp_upload_dir();
	$directory1 = $upload_dir['basedir']."/zengogallery";
	
	$images_folder = $directory1."/".$galleryname.'/main/';
	$thumbs_folder = $directory1."/".$galleryname.'/thumb/';
	
	$from_name = $image_name;
	$to_name = $image_name;
	
	///////////////////////////////////////////////////
	/////////////// DO NOT EDIT BELOW ///////////////
	///////////////////////////////////////////////////

	if (isset($_REQUEST['f'])) {
	  $save_to_file = intval($_REQUEST['f']) == 1;
	}

	if (isset($_REQUEST['q'])) {
	  $image_quality = intval($_REQUEST['q']);
	}

	if (isset($_REQUEST['t'])) {
	  $image_type = intval($_REQUEST['t']);
	}
	$max_x = intval(250);
	$max_y = intval(250);
	
	if (!file_exists($images_folder)) die('Images folder does not exist');
	if ($save_to_file && !file_exists($thumbs_folder)) die('Thumbnails folder does not exist');

	// Allocate all necessary memory for the image.
	// Special thanks to Alecos for providing the code.
	ini_set('memory_limit', '-1');

	// include image processing code
	include_once('image.class.php');

	$img = new Zubrag_image;

	// initialize
	$img->max_x        = $max_x;
	$img->max_y        = $max_y;
	$img->cut_x        = $cut_x;
	$img->cut_y        = $cut_y;
	$img->quality      = $image_quality;
	$img->save_to_file = $save_to_file;
	$img->image_type   = $image_type;

	// generate thumbnail
	$img->GenerateThumbFile($images_folder."/".$from_name, $thumbs_folder."/".$to_name);
}
?>
