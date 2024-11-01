<?php
function load_custom_wp_admin_style(){
	wp_register_style('style.css', plugins_url("/css/style.css", __FILE__), false, '1.0.0' );
	wp_enqueue_style('style.css');

	wp_register_style('popup.css', plugins_url("/css/popup.css", __FILE__), false, '1.0.0' );
	wp_enqueue_style('popup.css');
	
	wp_enqueue_script( 'check.js', plugins_url('/js/check.js', __FILE__), array(), '1.0.0', true );
	//wp_enqueue_script( 'zentabcontent.js', plugins_url('/js/zentabcontent.js', __FILE__), array(), '1.0.0', true );
}
add_action('admin_enqueue_scripts','load_custom_wp_admin_style');
do_action('admin_enqueue_scripts');
?>
