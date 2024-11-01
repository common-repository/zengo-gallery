<?php
function d_gallery_callback(){
require('include.php');
?>
<div class="main-title">
<h1><span>ZenGo Gallery</span></h1>
</div>
<div class="d-main">
	<div class="d-add-gallery">
		<h2>Add New Gallery</h2>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=d-gallery" method="post">
			<table>
				<tr>
					<th>Enter Gallery Name:</th>
					<td><input type="text" name="gallery_name" id="gallery_name" required class="gallery-name"></td>
					<td><input type="submit" name="add" id="add" value="Add" class="primary-btn primary-btn-add"></td>
				</tr>
				<tr>
					<td></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<?php
}
?>
