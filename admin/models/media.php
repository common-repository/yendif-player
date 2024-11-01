<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */

class Yendif_Player_Media_Model {

	/**
	 * List of available column names in "yendif_player_media" table.
	 *
	 * @since    1.0.0
	 *
	 * @var      Array
	 */
	private $columns = array(
		'id',
		'title',
		'type',
		'youtube',
		'mp4',
		'rtmp',
		'flash',
		'webm',
		'ogg',
		'mp3',
		'wav',
		'poster',
		'captions',
		'duration',
		'description',
		'playlists',
		'featured',
		'published',
		'createddate',
		'views',
		'post_id'
	);

	/**
	 * Constructor of this class.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {
	
	}
	
	/**
	 * Load all rows from "yendif_player_media" table.
	 *
	 * @since     1.0.0
	 */
	public function items() {
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_media';
		$sql = "SELECT * FROM $table";
		if ( isset($_POST['s']) ) {
			$sql .= " WHERE title LIKE '%" . mysql_real_escape_string( $_POST['s'] ) . "%'";
		}
		
		// If no sort, default to id
  		$orderby = ( ! empty( $_GET['orderby'] ) ) ? mysql_real_escape_string( $_GET['orderby'] ) : 'id';
		
  		// If no order, default to desc
  		$order = ( ! empty($_GET['order'] ) ) ? mysql_real_escape_string( $_GET['order'] ) : 'desc';
		
		$sql .= " ORDER BY $orderby $order";
		
		$_items = $wpdb->get_results( $sql, ARRAY_A );
		
		return $_items;

	}
	
	/**
	 * Load all playlist names from "yendif_player_playlists" table.
	 *
	 * @since     1.0.0
	 */
	public function playlists() {
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_playlists';
		$sql = "SELECT id, name FROM $table ORDER BY id DESC";
		$_items = $wpdb->get_results( $sql);
		
		$list = array();
		foreach ( $_items as $_item ) {
			$list[$_item->id] = $_item->name;
		}
		
		return $list;

	}
	
	/**
	 * Load any particular row from "yendif_player_media" table WHERE id = %d.
	 *
	 * @since     1.0.0
	 */
	public function item() {
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_media';
		$sql = "SELECT * FROM $table WHERE id = %d";		
		$row = $wpdb->get_row( $wpdb->prepare( $sql, $_GET['id'] ) );
		
		return $row;

	}
	
	/**
	 * Save or update "yendif_player_media" table with new data.
	 *
	 * @since     1.0.0
	 */
	public function save() {
	
		if ( ! check_admin_referer( 'yendif-player-nonce' ) ) return;
		
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_media';
		$data = array();
		foreach ( $_POST as $k => $v ) { 
  			if ( in_array( $k, $this->columns) ) {
				if ( $k == 'playlists' )  $v = ' ' . implode( ' ', $v) . ' ';
				$data[$k] = $v;
  			} 
		}
		
		$data['title'] = Yendif_Player_Admin_Functions::no_magic_quotes($data['title']);		
		$data['description'] = Yendif_Player_Admin_Functions::no_magic_quotes($data['description']);
		
		if ( $data['type'] == 'rtmp' ) {
			$data['mp4'] = $_POST['fallback'];
		}
		
		if ( $data['type'] == 'youtube' ) {
			if( ! $data['poster'] ) {
				$v = $this->get_youtube_video_id( $data['youtube'] );
				$data['poster'] = 'https://img.youtube.com/vi/'.$v.'/0.jpg';
			}
		}
		
		if ( ! array_key_exists( 'playlists', $data ) ) {
			$data['playlists'] = '';
		}
		
		if ( ! array_key_exists( 'featured', $data ) ) {
			$data['featured'] = 0;
		}	
		
		if ( ! array_key_exists( 'published', $data ) ) {
			$data['published'] = 0;
		}
		
		// Prepare data to store in wordpress posts table
		$current_user = wp_get_current_user();
		$post = array(
			'post_author'			=> $current_user->ID,
			'post_date'				=> date( 'Y-m-d H:i:s' ),
			'post_date_gmt'			=> date( 'Y-m-d H:i:s' ),
			'post_title'			=> $data['title'],
			'post_excerpt'			=> '',
			'post_status'			=> 'publish',
			'comment_status'		=> 'open',
			'ping_status'			=> 'closed',
			'post_password'			=> '',
			'post_name'				=> sanitize_title( $data['title'] ),
			'to_ping'				=> '',
			'pinged'				=> '',
			'post_modified'			=> date( 'Y-m-d H:i:s' ),
			'post_modified_gmt'		=> date( 'Y-m-d H:i:s' ),
			'post_content_filtered' => '',
			'post_parent'			=> 0,
			'menu_order'			=> 0,
			'post_type'				=> 'videopage',
			'post_mime_type'		=> '',
			'comment_count'			=> 0,
		);
		
		if ( isset($_GET['id']) ) {
		
			$id = (int) $_GET['id'];
			$wpdb->update( $table, $data, array( 'id' => $id ) );			
			
			if ( $data['type'] != 'audio' ) {
				$post['ID'] = $data['post_id'];	
				$post['post_content'] = '[yendifplayer video=' . $id . ']';
				$post['guid'] = get_site_url() . '/?post_type=videopage&#038;p=' . $data['post_id'];		
				wp_update_post( $post );
			};
			
		} else {
		
			$wpdb->insert( $table,  $data );
			
			if ( $data['type'] != 'audio' ) {
				$id = $wpdb->insert_id;
			
				$post['post_content'] = '[yendifplayer video=' . $id . ']';
				$post_id = wp_insert_post( $post );
				$wpdb->update( $table, array( 'post_id' => $post_id ), array( 'id' => $id ) );
				wp_update_post( array('ID' => $post_id, 'guid' => get_site_url() . '/?post_type=videopage&#038;p=' . $post_id) );
			};
			
		};	
	
		echo '<script type="text/javascript">window.location = "' . admin_url( 'admin.php?page=yendif-player-media' ) . '";</script>';
		exit(); 

	}
	
	/**
	 * Publish or unpublish selected rows.
	 *
	 * @since     1.0.0
	 */
	public function publish( $published ) {
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_media';
		$data = array( 'published' => $published );
		
		if( is_array( $_GET['id'] ) ) {
		
			foreach ( $_GET['id'] as $id ) {
				$wpdb->update( $table, $data, array( 'id' => $id ) );
			}
			
		} else {
		
			$wpdb->update( $table, $data, array( 'id' => $_GET['id'] ) );
			
		}	
	
		echo '<script type="text/javascript">window.location = "' . admin_url( 'admin.php?page=yendif-player-media' ) . '";</script>';
		exit(); 

	}
	
	/**
	 * Delete selected rows or single medium.
	 *
	 * @since     1.0.0
	 */
	public function delete() {
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_media';	
		
		if( is_array( $_GET['id'] ) ) {
		
			// Sanitize input array.
			$ids = array();
			foreach ( $_GET['id'] as $id ) {
				$ids[] = (int) $id;
			};
			
			// Get Post IDs for the deleted videos			
			$sql = "SELECT post_id FROM $table WHERE id IN (" . implode(',', $ids) . ")";
			$post_ids = $wpdb->get_col( $sql );
			
			// Delete videos from yendif_player table		
			$sql = "DELETE FROM $table WHERE id IN (". implode(',', $ids) .")";
			$wpdb->query( $sql );
			
			// Delete posts from wordpress posts table
			foreach ( $post_ids as $post_id ) {
				wp_delete_post( $post_id );
			};
			
		} else {
		
			$id = (int) $_GET['id'];
			
			// Get Post ID for the deleted video
			$sql = "SELECT post_id FROM $table WHERE id=" . $id;
			$post_id = $wpdb->get_var( $sql );			
			
			// Delete video from yendif_player table
			$sql = "DELETE FROM $table WHERE id=" . $id;
			$wpdb->query( $sql );
			
			// Delete post related to the video
			wp_delete_post( $post_id );
			
		}
	
		echo '<script type="text/javascript">window.location = "' . admin_url( 'admin.php?page=yendif-player-media' ) . '";</script>';
		exit(); 

	}
	
	/**
	 * Parse and returns YouTube Video ID.
	 *
	 * @since     1.0.0
	 */
	function get_youtube_video_id( $url ) {
	
    	$video_id = false;
    	$url = parse_url($url);
    	if(strcasecmp($url['host'], 'youtu.be') === 0) {
        	$video_id = substr($url['path'], 1);
    	} else if(strcasecmp($url['host'], 'www.youtube.com') === 0) {
        	if(isset($url['query'])) {
           		parse_str($url['query'], $url['query']);
            	if(isset($url['query']['v'])) {
               		$video_id = $url['query']['v'];
            	}
        	}
			
        	if($video_id == false) {
            	$url['path'] = explode('/', substr($url['path'], 1));
            	if(in_array($url['path'][0], array('e', 'embed', 'v'))) {
                	$video_id = $url['path'][1];
            	}
        	}
    	}
		
    	return $video_id;
		
	}

}