<?php
/** 
* Bootstrap file for getting the ABSPATH constant to wp-load.php 
* This is requried when a plugin requires access not via the admin screen. 
* 
* If the wp-load.php file is not found, then an error will be displayed 
* 
* @package WordPress 
* @since Version 2.6 
*/ 

/** Handle session_start */
session_start();

/** Define the server path to the file wp-config here, if you placed WP-CONTENT outside the classic file structure */
$path  = ''; // It should be end with a trailing slash 

if ( ! defined( 'WP_LOAD_PATH' ) ) { 

	/** classic root path if wp-content and plugins is below wp-config.php */ 
    $classic_root = dirname( dirname ( dirname ( dirname ( dirname( __FILE__ ) ) ) ) ) . '/' ; 

    if ( file_exists( $classic_root . 'wp-load.php' ) ) {
    	define( 'WP_LOAD_PATH', $classic_root ); 
	} else if ( file_exists( $path . 'wp-load.php' ) ) { 
    	define( 'WP_LOAD_PATH', $path ); 
    } else {
        exit( "Could not find wp-load.php" );
	}
} 

/** let's load WordPress **/
require_once( WP_LOAD_PATH . 'wp-load.php' ); 

/** Update views count of the current playing video **/
global $wpdb;	
						
$table = $wpdb->prefix . "yendif_player_media";	
$video_id = (int) $_GET['id'];

if ( ! isset( $_SESSION['yendif_views_' . $video_id] ) ) {			
    $sql = "SELECT views FROM $table WHERE id=" . $video_id;						
 	$old_views = $wpdb->get_var( $sql );
	$new_views = ($old_views) ? $old_views + 1 : 1;	
							
    $views = $wpdb->update( $table, array( 'views' => $new_views ), array( 'id' => $video_id ) );			
	$_SESSION['yendif_views_' . $video_id] = 1;				
}
