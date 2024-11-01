<?php
function d_shortcode_generator_callback(){
require('include.php');

$upload_dir = wp_upload_dir();
$directory1 = $upload_dir['basedir']."/zengogallery/";

?>
<div class="main-title">
	<h1><span>ZenGo Gallery</span></h1>
</div>
<div class="d-main">
	<script>
		function isNumberKey(evt){
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
			return true;
		}
	</script>
<?php
$display = "0";
if(isset($_POST['generate']) && $_POST['generate'] == "Generate"){
	$gallery_name = sanitize_text_field($_POST['gallery_name']);
	$display_type = sanitize_text_field($_POST['display_type']);
	$display_style = sanitize_text_field($_POST['display_style']);
	
	if($display_type =='thumb'){
		$trans_effect = sanitize_text_field($_POST['thumb_trans_effect']);
		$display_theme = "clean";
		$display_numbers = "none";
		$display_progressbar = "NA";
		$display_numbers_dots = "true";
		$display_slider_align = "NA";
		
		if($display_style == "pinterest"){
			$width = '';
			$height = '';
		}
		else{
			$width = intval($_POST['width']);
			$height = intval($_POST['height']);
		}
	}
	else{
		$trans_effect = sanitize_text_field($_POST['slider_trans_effect']);
		$display_style = "normal";
		$width = intval($_POST['width']);
		$height = intval($_POST['height']);
		$display_theme = sanitize_text_field($_POST['display_theme']);
		$display_numbers = sanitize_text_field($_POST['display_numbers_align']);
		$display_progressbar = sanitize_text_field($_POST['display_progressbar']);
		$display_numbers_dots = sanitize_text_field($_POST['display_numbers_dots']);
		$display_slider_align = sanitize_text_field($_POST['display_slider_align']);
	}
	if($width == ''){
		$width = 'NA';
	}
	
	if($height == ''){
		$height = 'NA';
	}
	
	$value = '[d_gallery id=&quot;'.$gallery_name.'&quot; display_type=&quot;'.$display_type.'&quot; effect=&quot;'.$trans_effect.'&quot; display_style=&quot;'.$display_style.'&quot; display_theme=&quot;'.$display_theme.'&quot; display_numbers=&quot;'.$display_numbers.'&quot; display_progressbar=&quot;'.$display_progressbar.'&quot; display_numbers_dots=&quot;'.$display_numbers_dots.'&quot; display_slider_align=&quot;'.$display_slider_align.'&quot;';
	if($height == 'NA'){
		$value .= ' display_height=&quot;'.$height.'&quot;';
	}
	else
	{
		$value .= ' display_height=&quot;'.$height.'px&quot;';
	}
	if($width == 'NA'){
		$value .= ' display_width=&quot;'.$width.'&quot;';
	}			
	else
	{
		$value .= ' display_width=&quot;'.$width.'px&quot;';
	}
	$value .= ']';
	
	$display = "1";
}
?>
<script>
jQuery(document).ready(function(){
	jQuery(".sht_code").each(function(){
		jQuery(this).hover(function(){
		  jQuery(this).select();
		});
		jQuery(this).click(function(){
		  jQuery(this).select();
		});
	});

	jQuery(document).ready(function(){
		jQuery('#display_theme').hide();
		jQuery('#display_numbers').hide();
		jQuery('#display_progressbar').hide();
		jQuery('#display_numbers_dots').hide();
		jQuery('#d-set-prop').text("Thumbnail");
		jQuery('#display_slider_align').hide();
	   
		jQuery('input[type="radio"]').click(function(){
			if(jQuery(this).attr('id') == '350'){
				jQuery('#slider-effects').show();
				jQuery('#thumb-effects').hide();
				jQuery('#display_style1').hide();
				jQuery('#display_theme').show();
				jQuery('#display_numbers').show();
				jQuery('#display_progressbar').show();
				jQuery('#display_numbers_dots').show();
				jQuery('#display_slider_align').show();
				jQuery("#352").prop("checked", true);
				jQuery('#d-set-prop').text("Slider");
			}
			else if(jQuery(this).attr('id') == '349'){
				jQuery('#slider-effects').hide();
				jQuery('#thumb-effects').show();
				jQuery('#display_style1').show();
				jQuery('#display_theme').hide();
				jQuery('#display_numbers').hide();
				jQuery('#display_numbers_dots').hide();
				jQuery('#display_progressbar').hide();
				jQuery('#display_slider_align').hide();
				jQuery('#d-set-prop').text("Thumbnail");
			}

			if(jQuery(this).attr('id') == '351'){
				jQuery('#height').val("");
				jQuery('#width').val("");
				jQuery('#height').prop("disabled", true );
				jQuery('#width').prop( "disabled", true );
			}
			else
			{
				jQuery('#height').prop("disabled", false );
				jQuery('#width').prop( "disabled", false );
			}
		});
	});
});
</script>
	<hr/><h2>Shortcode Generator</h2><hr/>
	<form id="shtcd_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=d-shortcode-generator" method="post">
		<div class="short-code-left">
			<h3>Properties</h3>
		<table class="short-code-form">
			<tr>
				<th align="left">Select Gallery Name </th>
				<td>
				<select name="gallery_name" class="ZenGoGallery-input">
				<?php $Gallery_names = scandir($directory1);
				$i = 0;
				foreach($Gallery_names as $Gallery_name){
					if(is_dir($directory1.$Gallery_name)){
						if($i > 1){
							?>
							<option value="<?php echo $Gallery_name; ?>"><?php echo $Gallery_name; ?></option>
							<?php
						}
					}
					$i++;
				}
				?>
				</select>
				</td>
			</tr>
			<tr>
				<th align="left">Select Display Type: </th>
				<td class="ZenGoGallery-redio">
					<input type="radio" id="349" value='thumb' name="display_type" checked>
					<label for="349">Thumb</label>
					<input type="radio" id="350" value="slider" name="display_type">
					<label for="350">Slider</label>
				</td>
			</tr>
		</table>
	</div>
	
	<div class="short-code-right">
			<h3><label id="d-set-prop"></label> Properties</h3>
		<table class="short-code-form">
			<tr>
				<th align="left">Select Transaction Effect: </th>
				<td>
					<div class="slider-effects" id="slider-effects" style='display:none' >
						<select name="slider_trans_effect">
							<option value="randomSmart">Random Smart</option>
							<option value="random">Random (On Refresh)</option>
							<option value="blind">Blind</option>
							<option value="blindHeight">BlindHeight</option>
							<option value="blindWidth">BlindWidth</option>
							<option value="block">Block</option>
							<option value="cube">Cube</option>
							<option value="cubeRandom">Cube Random</option>
							<option value="cubeStop">Cube Stop</option>
							<option value="cubeHide">Cube Hide</option>
							<option value="cubeJelly">Cube Jelly</option>
							<option value="cubeSize">Cube Size</option>
							<option value="cubeSpread">Cube Spread</option>
							<option value="cubeShow">Cube Show</option>
							<option value="cubeStopRandom">Cube Stop Random</option>
							<option value="circles">Circles</option>
							<option value="circlesInside">Circles Inside</option>
							<option value="circlesRotate">Circles Rotate</option>
							<option value="cut">Cut</option>
							<option value="directionTop">Direction Top</option>
							<option value="directionBottom">Direction Bottom</option>
							<option value="directionRight">Direction Right</option>
							<option value="directionLeft">Direction Left</option>
							<option value="downBars">Down Bars</option>
							<option value="fade">Fade</option>
							<option value="fadeFour">Fade Four</option>
							<option value="glassCube">Glass Cube</option>
							<option value="glassBlock">Glass Block</option>
							<option value="horizontal">Horizontal</option>
							<option value="hideBars">Hide Bars</option>
							<option value="paralell">Paralell</option>
							<option value="showBars">Show Bars</option>
							<option value="showBarsRandom">Show Bars Random</option>
							<option value="swapBars">Swap Bars</option>
							<option value="swapBarsBack">Swap Bars Back</option>
							<option value="swapBlocks">Swap Blocks</option>
							<option value="tube">Tube</option>
							<option value="upBars">Up Bars</option>
						</select>
					</div>
					<div class="thumb-effects" id="thumb-effects">
						<select name="thumb_trans_effect" class="ZenGoGallery-input">
							<option value="Fade">Fade</option>
						</select>
					</div>
				</td>
			</tr>
			
			<tr>
				<th align="left">Select Display Style: </th>
				<td class="ZenGoGallery-redio">
						<input type="radio" id="352" value="normal" name="display_style" checked>
						<label for="352">Normal</label>
						<div class="display_style1" id="display_style1">
						<input type="radio" id="351" value="pinterest" name="display_style">
						<label for="351">Pinterest</label>
					</div>
				</td>
			</tr>
			
			<tr class="display_height" id="display_height">
				<th align="left">Gallery Image Height: </th>
				<td><input type="number" name="height" id="height" min="10" class="ZenGoGallery-input" onkeypress="return isNumberKey(event)"></td>
			</tr>
			
			<tr class="display_width" id="display_width">
				<th align="left">Gallery Image Width: </th>
				<td><input type="number" name="width" id="width" min="10" class="ZenGoGallery-input" onkeypress="return isNumberKey(event)"></td>
			</tr>
			
			<tr class="display_theme" id="display_theme">
				<th align="left">Select Slider Theme: </th>
				<td>
					<select id="display_theme" name="display_theme">
						<option value="clean">Clean</option>
						<option value="minimalist">Minimalist</option>
						<option value="round">Round</option>
						<option value="Square">Square</option>
					</select>
				</td>
			</tr>
			
			<tr class="display_slider_align" id="display_slider_align">
				<th align="left">Slider Align: </th>
				<td>
					<select id="display_slider_align" name="display_slider_align">
						<option value="left">Left</option>
						<option value="center">Center</option>
						<option value="right">Right</option>
					</select>
				</td>
			</tr>
			
			<tr class="display_numbers" id="display_numbers">
				<th align="left">Display Numbers Align: </th>
				<td>
					<select id="display_numbers_align" name="display_numbers_align">
						<option value="right">Right</option>
						<option value="center">Center</option>
						<option value="left">Left</option>
						<option value="none">None</option>
					</select>
				</td>
			</tr>
			
			<tr class="display_numbers_dots" id="display_numbers_dots">
				<th align="left">Display Numbers/DOTS: </th>
				<td>
					<select id="display_numbers_dots" name="display_numbers_dots">
						<option value="true">Dots</option>
						<option value="false">Number</option>
					</select>
				</td>
			</tr>
			
			<tr class="display_progressbar" id="display_progressbar">
				<th align="left">Display Progressbar: </th>
				<td>
					<select id="display_progressbar" name="display_progressbar">
						<option value="true">True</option>
						<option value="false">False</option>
					</select>
				</td>
			</tr>
			
			<tr>
				<td colspan="2"><input type="submit" name="generate" id="generate" value="Generate" class="primary-btn  primary-btn-generate"></td>
			</tr>
		</table>
	   </div>
	</form>
<?php
if($display == "1"){ ?>
	<div class="shrt-code">
		<h4>Shortcode: <input readonly type="text" id="sht_code" name="sht_code" class="sht_code shtcode-text ZenGoGallery-shortcode" value="<?php echo $value; ?>" ></h4>
		<p><strong>NOTE:</strong> Copy this shortcode and paste is to the page where you want to display gallery.</p>
	</div>
<?php } ?>
</div>
<?php
}
?>
