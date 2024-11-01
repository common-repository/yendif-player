<?php
/**
 * Plugin Name:Yendif Player
 * Plugin URI:http://yendifplayer.com/wordpress-plugin-reference.html
 * Description:RESPONSIVE, LOCALIZED, AWESOME, FULLY FEATURED, FAST & THE MOST ADVANCED AUDIO, VIDEO GALLERY PLUGIN FOR WORDPRESS
 * Version:2.3
 * Author:Yendif Technologies Pvt Ltd.
 * Author URI:http://yendifplayer.com/
 * Text Domain:yendif-player

 * Copyright 2014 Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)

 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/*----------------------------------------------------------------------------*
 * define constants
 *----------------------------------------------------------------------------*/
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Name of the plugin
if ( ! defined( 'YENDIF_PLAYER_PLUGIN_NAME' ) ) {
    define( 'YENDIF_PLAYER_PLUGIN_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

// Unique identifier for the plugin. Used as Text Domain
if ( ! defined( 'YENDIF_PLAYER_PLUGIN_SLUG' ) ) {
    define( 'YENDIF_PLAYER_PLUGIN_SLUG', 'yendif-player' );
}

// Path to the plugin directory
if ( ! defined( 'YENDIF_PLAYER_PLUGIN_DIR' ) ) {
    define( 'YENDIF_PLAYER_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . YENDIF_PLAYER_PLUGIN_NAME . '/' );
}

// URL of the plugin
if ( ! defined( 'YENDIF_PLAYER_PLUGIN_URL' ) ) {
    define( 'YENDIF_PLAYER_PLUGIN_URL', WP_PLUGIN_URL . '/' . YENDIF_PLAYER_PLUGIN_NAME );
}

// The key value to check the plugin version
if ( ! defined( 'YENDIF_PLAYER_VERSION_KEY' ) ) {
    define( 'YENDIF_PLAYER_VERSION_KEY', 'yendif_player_version' );
}

// The actuall plugin version
if ( ! defined( 'YENDIF_PLAYER_VERSION_NUM' ) ) {
    define( 'YENDIF_PLAYER_VERSION_NUM', '2.3' );
}

/*----------------------------------------------------------------------------*
 * Installation, Uninstallation, Activation and Deactivation
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/install.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/uninstall.php' );

/*
 * Register hooks that are fired when the plugin is activated, deactivated or uninstalled.
 */
register_activation_hook( __FILE__, array( 'Yendif_Player_Install', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Yendif_Player_Uninstall', 'deactivate' ) );
register_uninstall_hook( __FILE__, array( 'Yendif_Player_Uninstall', 'uninstall' ) );
add_action( 'wp_loaded', array( 'Yendif_Player_Install', 'update_db_check' ) );

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
 
require_once( plugin_dir_path( __FILE__ ) . 'public/controller.php' );
add_action( 'plugins_loaded', array( 'Yendif_Player_Controller', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/includes/functions.php' );	
	require_once( plugin_dir_path( __FILE__ ) . 'admin/controller.php' );
	add_action( 'plugins_loaded', array( 'Yendif_Player_Admin_Controller', 'get_instance' ) );
	
}

/*----------------------------------------------------------------------------*
 * Common Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );
add_action( 'init', array( 'Yendif_Player_Functions', 'register_custom_post_types' ) );
add_action( 'widgets_init', array( 'Yendif_Player_Functions', 'register_widgets' ) );
