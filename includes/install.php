<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */

class Yendif_Player_Install {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	
	/**
	 * Constructor of this class.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
	
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
			
	}

	/**
	 * Called when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		if ( ! get_option( YENDIF_PLAYER_VERSION_KEY ) ) {
			$obj = self::get_instance();
			$obj->create_database_tables();
			$obj->insert_default_data();
			$obj->insert_gallery_post( array( 'title' => 'Latest Videos', 'content' => '[yendifgallery sort=latest]' ) );
			$obj->insert_gallery_post( array( 'title' => 'Popular Videos', 'content' => '[yendifgallery sort=popular]' ) );
			$obj->insert_gallery_post( array( 'title' => 'Featured Videos', 'content' => '[yendifgallery featured=1]' ) );
			$obj->insert_missing_posts();			
			
			update_option( YENDIF_PLAYER_VERSION_KEY, YENDIF_PLAYER_VERSION_NUM );
		};
		
		self::update_db_check();
				
	}

	/**
	 * Check and update yendif_player tables for the current plugin version.
	 *
	 * @since    1.2.0
	 */
	public static function update_db_check() {

		if ( get_option( YENDIF_PLAYER_VERSION_KEY ) != YENDIF_PLAYER_VERSION_NUM ) {
			$obj = self::get_instance();
			$obj->update();			
			   		
			update_option( YENDIF_PLAYER_VERSION_KEY, YENDIF_PLAYER_VERSION_NUM );
		};
		
	}
	
	/**
	 * Create required database tables.
	 *
	 * @since    1.0.0
	 */
	protected function create_database_tables() {
			
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		global $wpdb;
		
		$charset_collate = '';

		if ( ! empty( $wpdb->charset ) ) {
	  		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}

		if ( ! empty( $wpdb->collate ) ) {
	  		$charset_collate .= " COLLATE {$wpdb->collate}";
		}
		
		// Create settings table if not exists
    	$table = $wpdb->prefix . 'yendif_player_settings';
    	$sql = "CREATE TABLE IF NOT EXISTS " . $table . " (
              		`id` int(10) NOT NULL AUTO_INCREMENT,
					`analytics` varchar(25) NOT NULL,
					`autoplay` tinyint(4) NOT NULL,
					`autoplaylist` tinyint(4) NOT NULL,
					`controlbar` tinyint(4) NOT NULL,
					`currenttime` tinyint(4) NOT NULL,
					`desc_chars_limit` int(5) NOT NULL,
					`download` tinyint(4) NOT NULL,
					`duration` tinyint(4) NOT NULL,
					`embed` tinyint(4) NOT NULL,
              		`engine` varchar(10) NOT NULL,
					`fullscreen` tinyint(4) NOT NULL,
					`keyboard` tinyint(4) NOT NULL,
					`license` varchar(50) NOT NULL,
					`logo` varchar(255) NOT NULL,
					`loop` tinyint(4) NOT NULL,
					`no_of_cols` int(5) NOT NULL,
					`no_of_rows` int(5) NOT NULL,
					`playbtn` tinyint(4) NOT NULL,
					`playlist_height` int(10) NOT NULL,
					`playlist_position` varchar(10) NOT NULL,
					`playlist_width` int(10) NOT NULL,
					`playpause` tinyint(4) NOT NULL,					
					`progress` tinyint(4) NOT NULL,					
					`ratio` decimal(16,4) NOT NULL,
        			`responsive` tinyint(4) NOT NULL,
					`share` tinyint(4) NOT NULL,					
					`show_desc` tinyint(4) NOT NULL,
					`show_views` tinyint(4) NOT NULL,						
					`sort_order` varchar(25) NOT NULL,
					`theme` varchar(10) NOT NULL,
					`thumb_height` int(10) NOT NULL,
					`thumb_width` int(10) NOT NULL,
					`title_chars_limit` int(5) NOT NULL,
					`volume` int(5) NOT NULL,					
					`volumebtn` tinyint(4) NOT NULL,
					`width` int(10) NOT NULL,
					`height` int(10) NOT NULL,
              		PRIMARY KEY (id)
              		) $charset_collate;";
    
    	dbDelta( $sql );
		
		// Create media table if not exists
		$table = $wpdb->prefix . 'yendif_player_media';
    	$sql = "CREATE TABLE IF NOT EXISTS " . $table . " (
              		`id` int(10) NOT NULL AUTO_INCREMENT,
					`title` varchar(255) NOT NULL,
				    `type` varchar(10) NOT NULL,
					`youtube` varchar(255) NOT NULL,
					`mp4` varchar(255) NOT NULL,
					`rtmp` varchar(255) NOT NULL,
					`flash` varchar(255) NOT NULL,
					`webm` varchar(255) NOT NULL,
					`ogg` varchar(255) NOT NULL,
					`mp3` varchar(255) NOT NULL,
					`wav` varchar(255) NOT NULL,
					`poster` varchar(255) NOT NULL,
					`captions` text NOT NULL,
					`duration` varchar(15) NOT NULL,
					`description` text NOT NULL,
					`playlists` text NOT NULL,
					`featured` tinyint(4) NOT NULL,
					`published` tinyint(4) NOT NULL,
					`createddate` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
					`views` int(10) NOT NULL,
					`post_id` int(25) NOT NULL,
              		PRIMARY KEY (id)
              		) $charset_collate;";
    
    	dbDelta( $sql );
		
		// Create playlists table if not exists
		$table = $wpdb->prefix . 'yendif_player_playlists';
    	$sql = "CREATE TABLE IF NOT EXISTS " . $table . " (
              		`id` int(10) NOT NULL AUTO_INCREMENT,
					`name` varchar(255) NOT NULL,
					`image` VARCHAR(255) NOT NULL,					
					`published` tinyint(4) NOT NULL,
					`createddate` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
					`post_id` int(25) NOT NULL,
              		PRIMARY KEY (id)
              		) $charset_collate;";
    
    	dbDelta( $sql );				
		
	}
	
	/**
	 * Update yendif_player tables with default data.
	 *
	 * @since    1.0.0
	 */
	protected function insert_default_data() {
	
		global $wpdb;
		
		// Insert to settings table
    	$table = $wpdb->prefix . 'yendif_player_settings';
		$wpdb->insert( $table, array(
			'id'                => 1,
			'analytics'         => '',
			'autoplay'          => 0,
			'autoplaylist'      => 0,
			'controlbar'        => 1,
			'currenttime'       => 1,
			'desc_chars_limit'  => 150,
			'download'          => 1,
			'duration'          => 1,
			'embed'             => 1,
            'engine'            => 'flash',
			'fullscreen'        => 1,
			'keyboard'          => 1,
			'license'           => '',
			'logo'              => '',
			'loop'              => 0,
			'no_of_cols'        => 2,
			'no_of_rows'        => 2,
			'playbtn'           => 1,
			'playlist_height'   => 125,
			'playlist_position' => 'right',
			'playlist_width'    => 250,
			'playpause'         => 1,					
			'progress'          => 1,					
			'ratio'             => 0.5625,
        	'responsive'        => 1,
			'share'             => 1,
			'show_desc'         => 1,
			'show_views'        => 1,			
			'sort_order'        => 'latest',			
			'theme'             => 'black',
			'thumb_width'       => 145,		
			'thumb_height'      => 80,
			'title_chars_limit' => 75,
			'volume'            => 50,					
			'volumebtn'         => 1,
			'width'             => 640,		
			'height'            => 360
		) );
		
		// Insert to playlists table
    	$table = $wpdb->prefix . 'yendif_player_playlists';
		$wpdb->insert( $table, array(  
			'id'              => 1,
			'name'            => 'Sample Playlist',
			'image'           => '',			
			'published'       => 1
		) );
		
	}
	
	/**
	 * Update yendif_player tables for the current plugin version.
	 *
	 * @since    1.2.0
	 */
	protected function update() {
	
		global $wpdb;
		
		$obj = self::get_instance();
			
		// Version 2.0
		$table = $wpdb->prefix . 'yendif_player_settings';
		$wpdb->get_results( "SHOW COLUMNS FROM FROM table_name LIKE 'desc_chars_limit'" );
		$num_rows = $wpdb->num_rows;
		
		if( ! $num_rows ) {
			// Update settings table  
			$table = $wpdb->prefix . 'yendif_player_settings';  	
			$sql = "ALTER TABLE " . $table . "
						ADD `desc_chars_limit` int(5) NOT NULL AFTER `currenttime`,
						ADD `no_of_cols` int(5) NOT NULL AFTER `loop`,
						ADD `no_of_rows` int(5) NOT NULL AFTER `no_of_cols`,
						ADD `show_desc` tinyint(4) NOT NULL AFTER `responsive`,
						ADD `show_views` tinyint(4) NOT NULL AFTER `show_desc`,
						ADD `sort_order` varchar(25) NOT NULL AFTER `show_views`,
						ADD `thumb_height` int(10) NOT NULL AFTER `theme`,
						ADD `thumb_width` int(10) NOT NULL AFTER `thumb_height`,
						ADD `title_chars_limit` int(5) NOT NULL AFTER `thumb_width`";
					
   			$wpdb->query( $sql );
		
			// Update media table
    		$table = $wpdb->prefix . 'yendif_player_media';
			$sql = "ALTER TABLE " . $table . "
						ADD `featured` tinyint(4) DEFAULT '0' NOT NULL AFTER `playlists`,
						ADD `views` int(10) NOT NULL AFTER `createddate`,
						ADD `post_id` int(25) NOT NULL AFTER `views`";
		
			$wpdb->query( $sql );
		
			// Update playlists table
    		$table = $wpdb->prefix . 'yendif_player_playlists';
			$sql = "ALTER TABLE " . $table . "
						ADD `image` varchar(255) NOT NULL AFTER `name`,
						ADD `post_id` int(25) NOT NULL AFTER `published`";
						
   			$wpdb->query( $sql );
			
			// ...
			$obj->update_tables_data( '2.0' );
			$obj->insert_gallery_post( array( 'title' => 'Latest Videos', 'content' => '[yendifgallery sort=latest]' ) );
			$obj->insert_gallery_post( array( 'title' => 'Popular Videos', 'content' => '[yendifgallery sort=popular]' ) );
			$obj->insert_gallery_post( array( 'title' => 'Featured Videos', 'content' => '[yendifgallery featured=1]' ) );
			$obj->insert_missing_posts();
		}
		
		// Version 2.3
		$table = $wpdb->prefix . 'yendif_player_settings';
		$wpdb->get_results( "SHOW COLUMNS FROM FROM table_name LIKE 'download'" );
		$num_rows = $wpdb->num_rows;
		
		if( ! $num_rows ) {
			// Update settings table    
			$table = $wpdb->prefix . 'yendif_player_settings';	
			$sql = "ALTER TABLE " . $table . "
						ADD `download` tinyint(4) NOT NULL AFTER `desc_chars_limit`,
						ADD `share` tinyint(4) NOT NULL AFTER `responsive`";
					
   			$wpdb->query( $sql );
			
			// ...
			$obj->update_tables_data( '2.3' );
		}
				
	}
	
	/**
	 * Update yendif_player tables data for the current plugin version.
	 *
	 * @since    1.2.0
	 */
	protected function update_tables_data( $version = '1.0' ) {
	
		global $wpdb;
		
		// Version 2.0
		if( $version == '2.0' ) {
			// Update settings table
    		$table = $wpdb->prefix . 'yendif_player_settings';
			$data = array(
				'desc_chars_limit'  => 150,
				'no_of_cols'        => 2,
				'no_of_rows'        => 2,
				'show_desc'         => 1,
				'show_views'        => 1,
				'sort_order'        => 'latest',
				'thumb_width'       => 145,		
				'thumb_height'      => 80,
				'title_chars_limit' => 75
			);
			$wpdb->update( $table, $data, array( 'id' => 1 ) );
		}
		
		// Version 2.3
		if( $version == '2.3' ) {
			// Update settings table
    		$table = $wpdb->prefix . 'yendif_player_settings';
			$data = array(
				'download' => 0,
				'share'    => 1
			);
			$wpdb->update( $table, $data, array( 'id' => 1 ) );
		}
				
	}
	
	/**
	 * Insert post to the provided arguments.
	 *
	 * @since    1.2.0
	 */
	protected function insert_gallery_post( $args ) {
	
		global $wpdb;
		
		$post_id = $wpdb->get_results( 'SELECT ID FROM ' . $wpdb->prefix . 'posts WHERE post_type="videoplaylist" AND post_content="%'.$args['content'].'%" LIMIT 1' );
		
		if ( ! $post_id ) {
			$current_user = wp_get_current_user();
		
			// Prepare data to store in wordpress posts table			
			$post = array(
				'post_author'			=> $current_user->ID,
				'post_date'				=> date( 'Y-m-d H:i:s' ),
				'post_date_gmt'			=> date( 'Y-m-d H:i:s' ),
				'post_title'			=> $args['title'],
				'post_content' 			=> $args['content'],
				'post_excerpt'			=> '',
				'post_status'			=> 'publish',
				'comment_status'		=> 'closed',
				'ping_status'			=> 'closed',
				'post_password'			=> '',
				'post_name'				=> sanitize_title( $args['title'] ),
				'to_ping'				=> '',
				'pinged'				=> '',
				'post_modified'			=> date( 'Y-m-d H:i:s' ),
				'post_modified_gmt'		=> date( 'Y-m-d H:i:s' ),
				'post_content_filtered' => '',
				'post_parent'			=> 0,
				'menu_order'			=> 0,
				'post_type'				=> 'videoplaylist',
				'post_mime_type'		=> '',
				'comment_count'			=> 0
			);
			
			$post_id = wp_insert_post( $post );
			wp_update_post( array('ID' => $post_id, 'guid' => get_site_url() . '/?post_type=videoplaylist&#038;p=' . $post_id) );
		};
	       				
	}
	
	/**
	 * Insert missing posts for the videos & playlists.
	 *
	 * @since    1.2.0
	 */
	protected function insert_missing_posts() {
	
		global $wpdb;
		
		$current_user = wp_get_current_user();
		
		// Insert missing video posts
		$table = $wpdb->prefix . 'yendif_player_media';
		
		$sql = "SELECT id, title FROM $table WHERE type!='audio'";		
		$items = $wpdb->get_results( $sql, ARRAY_A );		
		
		foreach ( $items as $item ) {
			// Prepare data to store in wordpress posts table			
			$post = array(
				'post_author'			=> $current_user->ID,
				'post_date'				=> date( 'Y-m-d H:i:s' ),
				'post_date_gmt'			=> date( 'Y-m-d H:i:s' ),
				'post_title'			=> $item['title'],
				'post_content' 			=> '[yendifplayer video=' . $item['id'] . ']',
				'post_excerpt'			=> '',
				'post_status'			=> 'publish',
				'comment_status'		=> 'open',
				'ping_status'			=> 'closed',
				'post_password'			=> '',
				'post_name'				=> sanitize_title( $item['title'] ),
				'to_ping'				=> '',
				'pinged'				=> '',
				'post_modified'			=> date( 'Y-m-d H:i:s' ),
				'post_modified_gmt'		=> date( 'Y-m-d H:i:s' ),
				'post_content_filtered' => '',
				'post_parent'			=> 0,
				'menu_order'			=> 0,
				'post_type'				=> 'videopage',
				'post_mime_type'		=> '',
				'comment_count'			=> 0
			);
			
			$post_id = wp_insert_post( $post );
			wp_update_post( array('ID' => $post_id, 'guid' => get_site_url() . '/?post_type=videopage&#038;p=' . $post_id) );
			$wpdb->update( $table, array( 'post_id' => $post_id ), array( 'id' => $item['id'] ) );
		};
		
		// Insert missing playlist posts
		$table = $wpdb->prefix . 'yendif_player_playlists';
		
		$sql = "SELECT id, name FROM $table";		
		$items = $wpdb->get_results( $sql, ARRAY_A );		
		
		foreach ( $items as $item ) {
			// Prepare data to store in wordpress posts table			
			$post = array(
				'post_author'			=> $current_user->ID,
				'post_date'				=> date( 'Y-m-d H:i:s' ),
				'post_date_gmt'			=> date( 'Y-m-d H:i:s' ),
				'post_title'			=> $item['name'],
				'post_content' 			=> '[yendifgallery playlist=' . $item['id'] . ']',
				'post_excerpt'			=> '',
				'post_status'			=> 'publish',
				'comment_status'		=> 'closed',
				'ping_status'			=> 'closed',
				'post_password'			=> '',
				'post_name'				=> sanitize_title( $item['name'] ),
				'to_ping'				=> '',
				'pinged'				=> '',
				'post_modified'			=> date( 'Y-m-d H:i:s' ),
				'post_modified_gmt'		=> date( 'Y-m-d H:i:s' ),
				'post_content_filtered' => '',
				'post_parent'			=> 0,
				'menu_order'			=> 0,
				'post_type'				=> 'videoplaylist',
				'post_mime_type'		=> '',
				'comment_count'			=> 0
			);
			
			$post_id = wp_insert_post( $post );
			wp_update_post( array('ID' => $post_id, 'guid' => get_site_url() . '/?post_type=videoplaylist&#038;p=' . $post_id) );
			$wpdb->update( $table, array( 'post_id' => $post_id ), array( 'id' => $item['id'] ) );
		};
				
	}	

}