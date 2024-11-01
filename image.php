<?php
/*
Plugin Name: ZenGo Gallery
Plugin URI: http://www.zengo-web-services.com
Version: 1.0.0
Author: Zengo Web Services
Description: Separate Thumbnail Image from main Image, Custom Gallery Image Size by Zengo Web Services.
*/

add_action( 'admin_menu', 'register_d_gallery_menu_page' );
function register_d_gallery_menu_page() {
	global $submenu;
	add_menu_page( 'ZenGo Gallery', 'ZenGo Gallery', 'manage_options', 'd-gallery', 'd_gallery_menu_page', plugins_url('/images/gallery-icon.png',__FILE__),59.9999999);
	add_submenu_page( 'd-gallery', 'ZenGo Gallery', 'Add Gallery', 'manage_options', 'd-add-gallery', 'd_gallery_callback' );
	add_submenu_page( 'd-gallery', 'ZenGo Gallery', 'Thumbnail Creator', 'manage_options', 'd-ftp-manager', 'd_ftp_manager_callback' );
	add_submenu_page( 'd-gallery', 'ZenGo Gallery', 'Shortcode Generator', 'manage_options', 'd-shortcode-generator', 'd_shortcode_generator_callback' );
	$submenu['d-gallery'][0][0] = 'All Galleries';
}
function d_gallery_menu_page(){
require('include.php');

$upload_dir = wp_upload_dir();
$directory1 = $upload_dir['basedir']."/zengogallery/";

$plugin_url = plugins_url();
$no_acc = plugins_url("/no-access/index.html", __FILE__);

if(isset($_POST['add']) && $_POST['add'] == "Add"){
	$gallery_name = sanitize_text_field(preg_replace('/[^A-Za-z0-9 \-]/', '', $_POST['gallery_name']));
	$directory = $directory1.$gallery_name;
	
	if(!is_dir($directory))
	{
		mkdir($directory, 0755,true);
		copy($no_acc, $directory."/index.html");
		mkdir($directory."/thumb", 0755,true);
		mkdir($directory."/main", 0755,true);
		?>
			<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
			<p><strong><?php echo $gallery_name." Added."; ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
		<?php
	}
	else
	{ ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
		<p><strong><?php echo $gallery_name; ?> Already Exist.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
	<?php
	}
}

if(isset($_GET['delete'])){
	$delete = $_GET['delete'];
	if(is_dir($directory1."/".$delete."/thumb")){
		array_map('unlink', glob($directory1."/".$delete."/thumb/*"));
		rmdir($directory1."/".$delete."/thumb");
	}
	if(is_dir($directory1."/".$delete."/main")){
		array_map('unlink', glob($directory1."/".$delete."/main/*"));
		rmdir($directory1."/".$delete."/main");
	}
	if(is_dir($directory1."/".$delete)){
		array_map('unlink', glob($directory1."/".$delete."/*"));
		rmdir($directory1."/".$delete);
	}
	?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
		<p><strong><?php echo $delete." deleted."; ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
	<?php
}

if(isset($_GET['delete-image'])){
	$image = $_GET['delete-image'];
	$gallery_name = $_GET['edit-gallery'];
	if(file_exists($directory1."/".$gallery_name."/thumb/".$image) || file_exists($directory1."/".$gallery_name."/main/".$image)){
		array_map('unlink', glob($directory1."/".$gallery_name."/thumb/".$image));
		array_map('unlink', glob($directory1."/".$gallery_name."/main/".$image));
		?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
		<p><strong><?php echo $image." deleted."; ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
		<?php
	}
}

if(isset($_POST['submit']) && $_POST['submit'] == "Upload Image")
{
	if(isset($_FILES['files'])){
		$errors = array();
		foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
			$file_name = $_FILES['files']['name'][$key];
			$file_size = $_FILES['files']['size'][$key];
			$file_tmp = $_FILES['files']['tmp_name'][$key];
			$file_type = $_FILES['files']['type'][$key];
			if($file_size > 6291456){
				$errors[] = 'File size must be less than 6 MB';
			}
			include_once("thumb.php");
			$galleryname1 = $_GET['edit-gallery'];
			$desired_dir = $directory1."/".$_GET['edit-gallery']."/main/";
			
			if(empty($errors) == true){
				if(is_dir($desired_dir) == false){
					mkdir("$desired_dir", 0755);	//Create directory if it does not exist
				}
				if(file_exists("$desired_dir/".$file_name)==false){
					move_uploaded_file($file_tmp,$desired_dir."/".$file_name);
					d_create_thumbnail($file_name,$galleryname1);
				}else{
					try{
						$name = pathinfo($file_name, PATHINFO_FILENAME);
						$extension = pathinfo($file_name, PATHINFO_EXTENSION);
						$new_dir = $name.time().".".$extension;
						
						move_uploaded_file($file_tmp,$desired_dir."/".$new_dir);
						d_create_thumbnail($new_dir,$galleryname1);
					}
					catch(exception $e) {
						$errors .= $e;
						$errors .= '<br/>';
						continue;
					}
				}
			}else{
			  ?>
				<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
				<p><strong><?php print_r($errors); ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
			  <?php		
			}
		}
		if(empty($error)){
			?>
			<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
			<p><strong>Images Uploaded.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
		<?php
		}else{
			?>
			<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
			<p><strong>Images Not Uploaded. Try Again.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
		<?php
		}
	}
}

if(isset($_POST['bulk-delete']) && $_POST['bulk-delete'] == "Apply"){
	if(isset($_POST['bulk-select']) && $_POST['bulk-select'] == "delete"){
		$images = $_POST['chk_multiselect'];
		if(!empty($images)){
			foreach($images as $image){
				$gallery_name = $_GET['edit-gallery'];
				array_map('unlink', glob($directory1."/".$gallery_name."/thumb/".$image));
				array_map('unlink', glob($directory1."/".$gallery_name."/main/".$image));
			}
			?>
			<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
			<p><strong>Images Deleted.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
		<?php
		}
	}
}

if(isset($_POST['d-upload-media']) && $_POST['d-upload-media'] == "Add Selected Image")
{
	$galleryname1 = $_GET['edit-gallery'];
	$images = $_POST['checkbox'];
	
	include_once("thumb.php");
	foreach($images as $images){
		$image_name = explode("/",$images);
		if(file_exists($directory1.$galleryname1."/main/".end($image_name))){
			$name = pathinfo(end($image_name), PATHINFO_FILENAME);
			$extension = pathinfo(end($image_name), PATHINFO_EXTENSION);
			$new_image_name = $name.time().".".$extension;
			copy($images,$directory1.$galleryname1."/main/".$new_image_name);
			d_create_thumbnail($new_image_name,$galleryname1);
		}else{
			copy($images,$directory1.$galleryname1."/main/".end($image_name));
			d_create_thumbnail(end($image_name),$galleryname1);
		}
	}
	?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
		<p><strong>Images Added.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
	<?php
}
$rowsShown = 10;
?>

<script>
jQuery(document).ready(function($){
	jQuery('#data').after('<div id="nav"></div>');
	var rowsShown = <?php echo $rowsShown; ?>;
	var rowsTotal = jQuery('#data tbody tr').length;
	var numPages = rowsTotal/rowsShown;
	for(i = 0; i < numPages; i++) {
		var pageNum = i + 1;
		$('#nav').append('<a href="#" rel="'+i+'">'+pageNum+'</a> ');
	}
	jQuery('#data tbody tr').hide();
	jQuery('#data tbody tr').slice(0, rowsShown).show();
	jQuery('#nav a:first').addClass('active');
	jQuery('#nav a').bind('click', function(){
		jQuery('#select_all').removeAttr("checked");
		jQuery('.checkbox').each(function(){
			this.checked = false;
		});
		
		jQuery('#nav a').removeClass('active');
		jQuery(this).addClass('active');
		var currPage = jQuery(this).attr('rel');
		var startItem = currPage * rowsShown;
		var endItem = startItem + rowsShown;
		jQuery('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).css('display','table-row').animate({opacity:1}, 300);
	});
});
</script>
<div class="main-title">
	<h1><span>ZenGo Gallery</span></h1>
</div>
<div class="d-main">
	<?php 
	{ ?>
<script>
//Function To Display Popup
function d_add_div_show(){
	document.getElementById('d-add-popup').style.display = "block";
}
//Function to Hide Popup
function d_add_div_hide(){
	document.getElementById('d-add-popup').style.display = "none";
}
</script>
<div id="d-add-popup">
 <div id="d-add-popupContact">
  <div id="popup-content">
	<div class="d-add-gallery">
		<h2>Add New Gallery</h2>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=d-gallery" method="post">
			<table>
				<tr>
					<th>Enter Gallery Name:</th>
					<td><input type="text" name="gallery_name" id="gallery_name" required class="gallery-name"></td>
					<td><input type="submit" name="add" id="add" value="Add" class="primary-btn primary-btn-add"></td>
				</tr>
			</table>
		</form>
	</div>
  </div><?php
  echo '<img id="close" src="'.plugins_url('images/close.png',__FILE__).'" onclick="d_add_div_hide()">'; ?>
 </div>
</div>
	<?php }
	if(isset($_GET['action']) && $_GET['action'] == "edit-gallery" && isset($_GET['edit-gallery'])){ ?>
	<div class="d-add-image">
		<div class="d-upload">
			<script>
				//Function To Display Popup
				function div_show() {
					document.getElementById('d-gallery-popup').style.display = "block";
				}
				//Function to Hide Popup
				function div_hide(){
					document.getElementById('d-gallery-popup').style.display = "none";
				}
			</script>
			
			<h2>Edit <?php echo $_GET['edit-gallery']; ?> <input type="submit" id="popup" onclick="div_show()" value="Add Images" class="primary-btn primary-btn-add"></h2>
			<div id="d-gallery-popup">
				<div id="popupContact">
					<div id="popup-content">
							<div class="d-popup-select">
								<hr><h2>New Media Upload</h2><hr>
								<form id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=d-gallery&action=edit-gallery&edit-gallery=<?php echo $_GET['edit-gallery']; ?>" method="post" enctype="multipart/form-data"></form>
								<br/>
								<table>
									<tr>
										<th align="left" style="width:18%;">Select main Image</th>
										<td style="width:100%;">
											<label class="cabinet">
												<input type="file" name="files[]" id="main_ImageUpload" multiple="" form="form1" required class="custom-file-input">
											</label>
											</td>
										<td style="width:100%;"><img id="main_image" form="form1"></td>
										<td><input type="submit" value="Upload Image" name="submit" form="form1" class="primary-btn primary-btn-upload" >
											<!--<input type="submit" value="Close" onclick ="div_hide()" class="primary-btn">-->
										</td>
									</tr>
								</table>
								<br/>
							</div>
							<div class="d-media-files">
								<hr><h2>Add Images from other Galleries</h2><hr>
							<form action="" method="post" id="form-upload-media">
								<table style="width: 100%;">
									<thead>
										<tr>
											<td align="right" colspan="4">
											<input type="submit" value="Add Selected Image" name="d-upload-media" form="form-upload-media" class="primary-btn primary-btn-add" id="add-gallery">
										</tr>
									</thead>
									<tbody>
									<tr>
										<?php
											$dirs = array_filter(glob($directory1.'*'), 'is_dir');
											if($dirs){
												$row = 1;
												foreach($dirs as $dirs)
												{
													$gallery_name_dir = end(explode("/",$dirs));
													if($gallery_name_dir != $_GET['edit-gallery'])
													{
														$gallery_inner_dir = array_filter(glob($directory1."/".$gallery_name_dir.'/*'), 'is_dir');
														if($gallery_inner_dir){
															foreach($gallery_inner_dir as $inner_dirs1)
															{
																$inner_dirs = end(explode("/",$inner_dirs1));
																if($inner_dirs == "main")
																{
																	$j = 1;
																	$files = glob($directory1."/".$gallery_name_dir."/".$inner_dirs."/*");
																	
																	
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
																		if (in_array($ext, $supported_file)){
																			$imagename = explode("/",$image);
																		if($row > 4){
																			echo '<tr align="center">';
																			}?>
																				<td class="d-popup-img-style" align="center"><label><input type="checkbox" name="checkbox[]" value="<?php echo $image; ?>"><img src="<?php echo $upload_dir['baseurl']."/zengogallery/".$gallery_name_dir."/thumb/".end($imagename); ?>" alt="<?php end($imagename); ?>"></label></td>
																			<?php	
																			if($row%4 == 0){
																				echo '</tr>';
																				$row = 0;
																			}
																			$row++;
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
											if($j <= 1)
											{
												echo "<tr><td colspan='4'>No Images In other Galleries.</td></tr>";
											}
										?>
									</tr>
									</tbody>
								</table>
							</form>
							</div>
						</div><?php
					echo '<img id="close" src="'.plugins_url('images/close.png',__FILE__).'" onclick="div_hide()">'; ?>
				</div>
			</div>
		</div>
		
		<div class="d-image-list">
			<form name="form-multidelete" id="form-multidelete" action="<?php echo $_SERVER['PHP_SELF'].'?page=d-gallery&action=edit-gallery&edit-gallery='.$_GET['edit-gallery']; ?>" method="post"></form>
				<table>
					<tr>
						<td>
						<?php
							$files = glob($directory1."/".$_GET['edit-gallery']."/thumb/*");
							$k = 0;
							for ($i=0; $i<count($files); $i++)
							{
								$image = $files[$i];
								$supported_file = array(
									'gif',
									'jpg',
									'jpeg',
									'png'
								);
								$ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
								if (in_array($ext, $supported_file)){
								$imagename = explode("/",$image);
									$k++;
								} else {
									continue;
								}
							}
							
							$files = glob($directory1."/".$_GET['edit-gallery']."/main/*");
							$pending = 0;
							for ($i=0; $i<count($files); $i++)
							{
								$image = $files[$i];
								$supported_file = array(
									'gif',
									'jpg',
									'jpeg',
									'png'
								);
								$ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
								if (in_array($ext, $supported_file)){
								$imagename = explode("/",$image);
									$pending++;
								} else {
									continue;
								}
							}
							$pending1 = $pending - $k;
						?>
						<strong>Images </strong>(<?php echo $k; ?>)  |  <strong> Thumbnail Pending </strong>(<?php echo ($pending1); ?>)</td>
					</tr>
					</table>
					<table style="width:100%;">
					<tr>
						<td>
							<select name="bulk-select" form="form-multidelete">
									<option value="">Bulk Actions</option>
									<option value="delete">Delete</option>
							</select>
						</td>
						<td><input type="submit" name="bulk-delete" value="Apply" form="form-multidelete" class="primary-btn primary-btn-apply"></td>
						<td style="width:83%;" align="right"><div id="nav"></div></td>
						<td style="width:2%;"></td>
					</tr>
				</table>
			<table border="0" width="100%" id="data" class="first-nav">
				<thead>
					<tr style="background-color: rgba(221, 221, 221, 0.8);font-size:15px;">
						<th style="width:5%;"><input type="checkbox" id="select_all"></th>
						<th style="width:5%;">No.</th>
						<th style="width:40%;">Title</th>
						<th style="width:20%;">Images</th>
						<th style="width:20%;">Delete</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$dirs = array_filter(glob($directory1.'*'), 'is_dir');
					if($dirs){
						foreach($dirs as $dirs)
						{
							$gallery_name_dir = end(explode("/",$dirs));
							if($gallery_name_dir == $_GET['edit-gallery'])
							{
								$gallery_inner_dir = array_filter(glob($directory1."/".$gallery_name_dir.'/*'), 'is_dir');
								if($gallery_inner_dir){
									foreach($gallery_inner_dir as $inner_dirs1)
									{
										$inner_dirs = end(explode("/",$inner_dirs1));
										if($inner_dirs == 'thumb')
										{
											$j = 1;
											$files = glob($directory1."/".$gallery_name_dir."/".$inner_dirs."/*");
											
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
												if (in_array($ext, $supported_file)){
													$imagename = explode("/",$image);
													if($j%2 == 0){
														$color = 'rgba(221, 221, 221, 0.6)'; }
													else
													{
														$color = 'rgba(221, 221, 221, 0.3)';
													}
													$bare_url_delete = $_SERVER['PHP_SELF']."?page=d-gallery&action=edit-gallery&edit-gallery=".$_GET['edit-gallery']."&delete-image=".end($imagename);
													$complete_url_delete = wp_nonce_url( $bare_url_delete, 'trash-post_'.$post->ID );
													?>
													<tr align="center" style="background-color: <?php echo $color; ?>;">
														<td><input type="checkbox" class="checkbox" name="chk_multiselect[]" value="<?php echo end($imagename); ?>" form='form-multidelete'></td>
														<td><?php echo $j; ?>.</td>
														<td align="center"><?php echo pathinfo(end($imagename), PATHINFO_FILENAME); ?></td>
														<td><img src="<?php echo $upload_dir['baseurl']."/zengogallery/".$gallery_name_dir."/thumb/".end($imagename); ?>" alt="<?php end($imagename); ?>" max-width="100px" max-height="100px" class="edit-img-gallery"></td>
														<td><a href="<?php echo $complete_url_delete; ?>">
														
														<?php
														echo '<img src="'.plugins_url('/images/delete.png',__FILE__).'">';
														?>
														</a></td>
													</tr>
												<?php
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
				?>
				</tbody>
			</table>
			<hr/>
		</div>
		
	</div>
<?php
	}else{ ?>
		<div class="d-add-gallery">
			<table>
				<tr>
					<td></td>
					<td><h2>Gallery <input type="submit" name="add_new" id="add_new" value="Add New"  onclick="d_add_div_show()" class="primary-btn primary-btn-add"></h2></td>
				</tr>
			</table>
	</div>
	<div class="d-edit-gallery">
		<?php 
		$dirs = array_filter(glob($directory1.'*'), 'is_dir');
		if($dirs){
		?>
		<h2>Edit Gallery</h2>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=d-gallery" method="post">
			<table border="0" width="100%" cellspacing="10" class="first-nav">
				<tr>
					<th>Sr. No.</th>
					<th>Gallery Name</th>
					<th>Images Count</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
					<?php
						$j = 1;
						foreach($dirs as $dirs)
						{
							$gallery_name_dir = end(explode("/",$dirs));
							$files = glob($directory1."/".$gallery_name_dir."/thumb/*");
							$k = 0;
							for ($i=0; $i<count($files); $i++)
							{
								$image = $files[$i];
								$supported_file = array(
									'gif',
									'jpg',
									'jpeg',
									'png'
								);
								$ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
								if (in_array($ext, $supported_file)){
								$imagename = explode("/",$image);
									$k++;
								} else {
									continue;
								}
							}
							$bare_url_edit = $_SERVER['PHP_SELF']."?page=d-gallery&action=edit-gallery&edit-gallery=".$gallery_name_dir;
							$complete_url_edit = wp_nonce_url($bare_url_edit, 'trash-post_'.$post->ID );
							
							$bare_url_delete = $_SERVER['PHP_SELF']."?page=d-gallery&delete=".$gallery_name_dir;
							$complete_url_delete = wp_nonce_url($bare_url_delete, 'trash-post_'.$post->ID );
						?>
					<tr align="center">
						<td><?php echo $j; ?></td>
						<td><a href="<?php echo $complete_url_edit; ?>"><?php echo $gallery_name_dir; ?></a></td>
						<td><?php echo $k; ?></td>
						<td><a href="<?php echo $complete_url_edit; ?>">
								<?php
								echo '<img src="'.plugins_url('/images/edit.png',__FILE__).'">';
								?>
							</a>
						</td>
						<td><a href="<?php echo $complete_url_delete; ?>">
								<?php
								echo '<img src="'.plugins_url('/images/delete.png',__FILE__).'">';
								?>
							</a>
						</td>
					</tr>	
					<?php $j++; 
					} ?>
			</table>
		</form>
		<?php } ?>
	</div>
<?php } ?>
</div>
<?php
}
require('manage-gallery.php');
require('shortcode.php');
require('ftp-manage.php');
require('display.php');
?>
