<?php
function d_gallery($atts){
ob_start();
extract(shortcode_atts(
	array(
		'id' => '',
		'display_type' => '',
		'effect' => '',
		'display_style' => '',
		'display_height' => '',
		'display_width' => '',
		'display_theme' => '',
		'display_numbers' => '',
		'display_progressbar' => '',
		'display_numbers_dots' => '',
		'display_slider_align' => '',
	),$atts)
);
// Code
if($id != ""){
	if($effect == "random"){
		$effects =	array(block, cube, cubeRandom,  cubeStop, cubeHide, cubeSize, horizontal, showBars, showBarsRandom, tube, fade, fadeFour, paralell, blind, blindHeight, blindWidth, directionTop, directionBottom, directionRight, directionLeft, cubeStopRandom, cubeSpread, cubeJelly, glassCube, glassBlock, circles, circlesInside, circlesRotate, cubeShow, upBars, downBars, hideBars, swapBars, swapBarsBack, swapBlocks, cut, random, randomSmart);
		$tra_effect = $effects[rand(0,37)];
	}
	else{
		$tra_effect = $effect;
	}

$upload_dir = wp_upload_dir();
$directory1 = $upload_dir['basedir']."/zengogallery/";

wp_enqueue_style('style.css', plugins_url("/css/style.css", __FILE__));

if($display_style == 'pinterest'){
	wp_enqueue_style('pin.css', plugins_url("/css/pin.css", __FILE__));
}

if($display_type == 'slider'){
	wp_enqueue_style('styles1.css', plugins_url("/css/skitter.styles.css", __FILE__));
	wp_enqueue_script('easing.js', plugins_url("js/jquery.easing.1.3.js", __FILE__));
	wp_enqueue_script('skitter.js', plugins_url("js/jquery.skitter.js", __FILE__));
?>
<script type="text/javascript" language="javascript">
jQuery(document).ready(function($){
	jQuery('.box_skitter_large').skitter({
		theme: '<?php echo $display_theme; ?>',
		<?php if($display_numbers == 'none'){ ?>
			numbers_align: 'center',
		<?php }else{ ?>
			numbers_align: '<?php echo $display_numbers; ?>',
		<?php } ?>
		progressbar: <?php echo $display_progressbar; ?>, 
		dots: <?php echo $display_numbers_dots; ?>,
		preview: false
	});
});
</script>
<?php if($display_numbers == 'none'){ ?>
	<style>
		span.info_slide_dots {
			display: none !important;
		}
	</style>
	<?php
	}
}
else
{
	wp_enqueue_style('prettyPhoto.css', plugins_url("/css/prettyPhoto.css", __FILE__));
	wp_enqueue_script('prettyPhoto.js', plugins_url("/js/jquery.prettyPhoto.js", __FILE__));
	wp_enqueue_script('functionszeno.js', plugins_url("/js/functions.js", __FILE__));
}
if($display_type == 'thumb'){
	if($display_height != 'NA' || $display_width != 'NA'){
		echo '<style>
			.clearfix img{';
			
			if($display_height == 'NA'){
				$display_height = '50px';
			}
			echo 'height: '.$display_height.';';
			
			if($display_width == 'NA'){
				$display_width = '50px';
			}
			echo 'width: '.$display_width.';';
			
			echo '}
			.main ul li { height: '.$display_height.'; width: '.$display_width.'; }
			  </style>';
	}
	if($display_style == 'pinterest'){
	?>
		<div id="main-search-pin">
		<div id="columns">
	<?php
	}
	else
	{
		?>
		<div class="main">
		<ul class="gallery1 clearfix">
		<?php
	}
}
else
	if($display_type == 'slider'){
?>
<div id="d-page1">
	<div id="content1">
		<div class="border_box" align="<?php echo $display_slider_align; ?>">
			<div class="box_skitter box_skitter_large">
				<ul>
			<?php }
					$dirs = array_filter(glob($directory1.'*'), 'is_dir');
					if($dirs){
						foreach($dirs as $dirs)
						{
							$gallery_name_dir = end(explode("/",$dirs));
							if($gallery_name_dir == $id)
							{
								$gallery_found = 1;
								$gallery_inner_dir = array_filter(glob($directory1."/".$gallery_name_dir.'/*'), 'is_dir');
								if($gallery_inner_dir){
									foreach($gallery_inner_dir as $inner_dirs1)
									{
										$inner_dirs = end(explode("/",$inner_dirs1));
										if($inner_dirs == 'thumb')
										{
											$j = 1;
											$files = scandir($directory1."/".$gallery_name_dir."/".$inner_dirs,0);
											
											for($i=0; $i<count($files); $i++)
											{
												$image = $files[$i];
												$supported_file = array(
													'gif',
													'jpg',
													'jpeg',
													'png'
												);
												$ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
												if(in_array($ext, $supported_file)){
													$imagename = explode("/",$image); 
													if($display_type == 'slider'){ ?>
													<li>
														<a href="#image<?php echo $j; ?>">
															<img src="<?php echo $upload_dir['baseurl']."/zengogallery/".$gallery_name_dir."/main/".end($imagename); ?>" style="height: 100%;width: 100%;" class="<?php echo $tra_effect; ?>" />
														</a>
														<div class="label_text">
															<p><?php echo pathinfo(end($imagename), PATHINFO_FILENAME); ?></p>
														</div>
													</li>
												<?php
													}
													else if($display_type == 'thumb'){
														if($display_style == 'pinterest'){
															echo '<div class="pin1"><ul>';
															 ?>
															<li>
																<div class="pin">
																	<a href="<?php echo $upload_dir['baseurl']."/zengogallery/".$gallery_name_dir."/main/".end($imagename); ?>" rel="prettyPhoto[gallery2]" title="<?php echo pathinfo(end($imagename), PATHINFO_FILENAME); ?>">
																		<img src="<?php echo $upload_dir['baseurl']."/zengogallery/".$gallery_name_dir."/thumb/".end($imagename); ?>" width="100" height="100" alt="<?php echo pathinfo(end($imagename), PATHINFO_FILENAME); ?>" />
																	</a>
																</div>
															</li>
														<?php
														echo '<p>'.pathinfo(end($imagename), PATHINFO_FILENAME).'</p>';
														echo '</ul></div>';
														}
														else
														{ ?>
														<li>
															<a href="<?php echo $upload_dir['baseurl']."/zengogallery/".$gallery_name_dir."/main/".end($imagename); ?>" rel="prettyPhoto[gallery1]" title="<?php echo pathinfo(end($imagename), PATHINFO_FILENAME); ?>">
																<img src="<?php echo $upload_dir['baseurl']."/zengogallery/".$gallery_name_dir."/thumb/".end($imagename); ?>" width="100" height="100" alt="<?php echo pathinfo(end($imagename), PATHINFO_FILENAME); ?>" />
															</a>
														</li>
														<?php
														}
													}
												} else {
													continue;
												}
												$j++;
											}
										}
									}
								}
							}
						}
					}
					if($display_type == 'slider'){
						if($display_height == 'NA' || $display_width == 'NA'){
							if($display_height == 'NA'){
								$display_height = '340px';
							}
							
							if($display_width == 'NA'){
								$display_width = '600px';
							}
						}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
<style>
.box_skitter img{
    width: <?php echo $display_width; ?> !important;
    height: <?php echo $display_height; ?> !important;
}
.box_skitter,.box_skitter_large{
    width: <?php echo $display_width; ?> !important;
    height: <?php echo $display_height; ?> !important;
}
.container_skitter{
    width: <?php echo $display_width; ?> !important;
    height: <?php echo $display_height; ?> !important;
}
</style>
<?php
}
else
	if($display_type == 'thumb'){
		if($display_style == 'pinterest'){
		?>
			</div>
		</div>
		<?php
		}
		else
		{
			?>
			</ul>
		</div>
		<?php
		}
	}
}

if($gallery_found != 1){
	echo "Please Check Gallery ID or use 'Shortcode Generator'.";
}

return ob_get_clean();
}

add_shortcode('d_gallery','d_gallery');
add_filter('widget_text', 'do_shortcode');
?>
