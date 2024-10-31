<?php
/*
Plugin Name: Prouptime 
Description: Prouptime monitors your Wordpress Blog and alerts you if it's not reachable. 
Author: Stefan Grothkopp
Version: 0.1
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


add_action('admin_menu', 'prouptime_setup_menu');
 
function prouptime_setup_menu(){
        add_menu_page( 'Prouptime', 'Prouptime', 'manage_options', 'prouptime', 'prouptime_init_admin' );
}
 
function prouptime_init_admin(){

	require_once plugin_dir_path( __FILE__ ) . 'includes/prouptime_init.php';
	prouptime_admin_init();
}
 


 
?>
