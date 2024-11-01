<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */

class Yendif_Player_Functions {
	
	/**
	 * Constructor of this class.
	 *
	 * @since     1.2.0
	 */
	private function __construct() {

	}
	
	/**
	 * Register custom post types.
	 *
	 * @since    1.2.0
	 */
	public static function register_custom_post_types() {
		
		
		// Register custom post type for video page.
		$args = array(
			'labels'             => array(
				'name'               => _x( 'Yendif Video', 'post type general name', YENDIF_PLAYER_PLUGIN_SLUG ),
				'singular_name'      => _x( 'Video Item', 'post type singular name', YENDIF_PLAYER_PLUGIN_SLUG ),
				'add_new'            => _x( 'Add New', 'video item', YENDIF_PLAYER_PLUGIN_SLUG ),
				'add_new_item'       => __( 'Add New Video Item', YENDIF_PLAYER_PLUGIN_SLUG ),
				'new_item'           => __( 'New Video Item', YENDIF_PLAYER_PLUGIN_SLUG ),
				'edit_item'          => __( 'Edit Video Item', YENDIF_PLAYER_PLUGIN_SLUG ),
				'view_item'          => __( 'View Video Item', YENDIF_PLAYER_PLUGIN_SLUG ),
				'search_items'       => __( 'Search Video', YENDIF_PLAYER_PLUGIN_SLUG ),			
				'not_found'          => __( 'No videos found.', YENDIF_PLAYER_PLUGIN_SLUG ),
				'not_found_in_trash' => __( 'No videos found in Trash.', YENDIF_PLAYER_PLUGIN_SLUG ),
				'parent_item_colon'  => ''
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => false,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' )
		);
				
		register_post_type( 'videopage', $args );
		
		// Register custom post type for playlist page.
		$args = array(
			'labels'             => array(
				'name'               => _x( 'Yendif Video Playlist', 'post type general name', YENDIF_PLAYER_PLUGIN_SLUG ),
				'singular_name'      => _x( 'Video Playlist Item', 'post type singular name', YENDIF_PLAYER_PLUGIN_SLUG ),
				'add_new'            => _x( 'Add New Playlist', 'video item', YENDIF_PLAYER_PLUGIN_SLUG ),
				'add_new_item'       => __( 'Add New Playlist Item', YENDIF_PLAYER_PLUGIN_SLUG ),
				'new_item'           => __( 'New Playlist Item', YENDIF_PLAYER_PLUGIN_SLUG ),
				'edit_item'          => __( 'Edit Playlist Item', YENDIF_PLAYER_PLUGIN_SLUG ),
				'view_item'          => __( 'View Playlist Item', YENDIF_PLAYER_PLUGIN_SLUG ),
				'search_items'       => __( 'Search Playlist', YENDIF_PLAYER_PLUGIN_SLUG ),			
				'not_found'          => __( 'No playlists found.', YENDIF_PLAYER_PLUGIN_SLUG ),
				'not_found_in_trash' => __( 'No playlists found in Trash.', YENDIF_PLAYER_PLUGIN_SLUG ),
				'parent_item_colon'  => ''
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => false,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' )
		);
		
		register_post_type( 'videoplaylist', $args );
				
	}
	
	/**
	 * Register yendif player widgets.
	 *
	 * @since    1.2.0
	 */
	public static function register_widgets() {
		
		require_once( YENDIF_PLAYER_PLUGIN_DIR . 'widgets/videos/widget.php' );
		register_widget( 'Yendif_Videos_Widget' );
		
		require_once( YENDIF_PLAYER_PLUGIN_DIR . 'widgets/latest-videos/widget.php' );
		register_widget( 'Yendif_Latest_Videos_Widget' );	
		
		require_once( YENDIF_PLAYER_PLUGIN_DIR . 'widgets/popular-videos/widget.php' );
		register_widget( 'Yendif_Popular_Videos_Widget' );	
		
		require_once( YENDIF_PLAYER_PLUGIN_DIR . 'widgets/featured-videos/widget.php' );
		register_widget( 'Yendif_Featured_Videos_Widget' );
		
		require_once( YENDIF_PLAYER_PLUGIN_DIR . 'widgets/related-videos/widget.php' );
		register_widget( 'Yendif_Related_Videos_Widget' );
				
	}
	
	/**
	 * Trim the input content
	 *
	 * @since     1.2.0
	 *
	 * @return      string		Trimmed content
	 */
	public static function Truncate( $text, $length = 0 ) {
	
		$text = strip_tags($text);
    	if ($length > 0 && strlen($text) > $length) {
        	$tmp = substr($text, 0, $length);
            $tmp = substr($tmp, 0, strrpos($tmp, ' '));

            if (strlen($tmp) >= $length - 3) {
            	$tmp = substr($tmp, 0, strrpos($tmp, ' '));
            }
 
            $text = $tmp.'...';
        }
 
        return $text;
		
	}

}