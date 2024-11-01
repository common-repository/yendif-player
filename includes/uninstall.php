<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */

class Yendif_Player_Uninstall {

	/**
	 * Constructor of this class.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

	}
	
	/**
	 * Called when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

	}
	
	/**
	 * Called when the plugin is uninstalled.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {
	
		global $wpdb;

		// Delete videopage post type
		$wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_type="videopage"' );
		
		// Delete playlist post type
		$wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_type="videoplaylist"' );
		
		// Delete settings table if exists
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'yendif_player_settings' );
	
		// Delete videos table if exists
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'yendif_player_media' );
		
		// Delete playlists table if exists
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'yendif_player_playlists' );
	
		// Delete options value if exists
		delete_option( YENDIF_PLAYER_VERSION_KEY, YENDIF_PLAYER_VERSION_NUM );
	
	}

}