<?php
function d_ftp_manager_callback(){
require('include.php');
$upload_dir = wp_upload_dir();
$directory1 = $upload_dir['basedir']."/zengogallery/";
$directory = $upload_dir['baseurl']."/zengogallery/";
	
if(isset($_GET['upload_images'])){
	$galleryname = $_GET['upload_images'];
	include_once("thumb.php");

	$dirs = array_filter(glob($directory1.'*'), 'is_dir');
	if($dirs){
		foreach($dirs as $dirs)
		{
			$gallery_name_dir = end(explode("/",$dirs));
			if($gallery_name_dir == $galleryname)
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
								if(in_array($ext, $supported_file)){
									$imagename = explode("/",$image);
									if(!file_exists($directory1."/".$galleryname."/thumb/".end($imagename))){
										d_create_thumbnail(end($imagename),$galleryname);
									}
								}
								else{
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
}

?>
<div class="main-title">
		<h1><span>ZenGo Gallery</span></h1>
</div>
	<?php 
		$dirs = array_filter(glob($directory1.'*'), 'is_dir');
		if($dirs){
		?>
<div class="d-main">	
	<div class="d-ftp-manager">		
		<h2>Upload Images to the Gallery</h2>
		<h3>Gallery List</h3>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=d-ftp-manager" method="post" >
			<table border="0" width="100%" class="first-nav">
				<tr>
					<th>Sr. No.</th>
					<th>Gallery Name</th>
					<th>Images Added</th>
					<th>Thumbnail Pending</th>
					<th>Create Thumbnail</th>
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
							$files = glob($directory1."/".$gallery_name_dir."/main/*");
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
							$pending = $pending - $k;
					?>
					<tr align="center">
						<td><?php echo $j; ?></td>
						<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=d-gallery&action=edit-gallery&edit-gallery=<?php echo $gallery_name_dir; ?>"><?php echo $gallery_name_dir; ?></a></td>
						<td><?php echo $k; ?></td>
						<td><?php echo $pending; ?></td>
						<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=d-ftp-manager&upload_images=<?php echo $gallery_name_dir; ?>">Create Thumbnail</a></td>
					</tr>	
					<?php $j++; 
					} ?>
			</table>
		</form>
		
		
	</div>
		
</div>
<?php } ?>
			<div class="d-gallery-note">
				<h3>NOTE: You can Upload Images from FTP with these easy Steps.</h3>
				<ul>
					<li><strong>Step 1.</strong> Copy All your main Images</li>
					<li><strong>Step 2.</strong> Go to : /wp-content/uploads/zengogallery/{your Gallery name}/main/</li>
					<li><strong>Step 3.</strong> Paste all files to this folder</li>
					<li><strong>Step 4.</strong> Go to : Dashboard/ZenGo Gallery/Thumbnail Creator/{your Gallery name}/Create Thumbnail</li>
					<li><strong>Step 5.</strong> Done. </li> 
				 </ul>
			 </div>
			
<?php
}
?>
