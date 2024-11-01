<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */

class Yendif_Player_Playlists_Model {

	/**
	 * List of available column names in "yendif_player_playlists" table.
	 *
	 * @since    1.0.0
	 *
	 * @var      Array
	 */
	private $columns = array(
		'id',
		'name',
		'image',
		'published',
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
	 * Load all rows from "yendif_player_playlists" table.
	 *
	 * @since     1.0.0
	 */
	public function items() {
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_playlists';
		$sql = "SELECT * FROM $table";
		if(isset($_POST['s'])) {
			$sql .= " WHERE name LIKE '%" . mysql_real_escape_string( $_POST['s'] ) . "%'";
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
	 * Load any particular row from "yendif_player_playlists" table WHERE id = %d.
	 *
	 * @since     1.0.0
	 */
	public function item() {
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_playlists';
		$sql = "SELECT * FROM $table WHERE id = %d";		
		$row = $wpdb->get_row( $wpdb->prepare( $sql, $_GET['id'] ) );
		
		return $row;

	}
	
	/**
	 * Save or update "yendif_player_playlists" table with new data.
	 *
	 * @since     1.0.0
	 */
	public function save() {
	
		if ( ! check_admin_referer( 'yendif-player-nonce' ) ) return;
		
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_playlists';
		$data = array();
		foreach ( $_POST as $k => $v ) { 
  			if ( in_array( $k, $this->columns) ) {
				$data[$k] = $v;
  			} 
		}
		$data['name'] = Yendif_Player_Admin_Functions::no_magic_quotes($data['name']);	
		
		if ( ! array_key_exists( 'published', $data ) ) {
			$data['published'] = 0;
		}
		
		// Prepare data to store in wordpress posts table
		$current_user = wp_get_current_user();
		$post = array(
			'post_author'			=> $current_user->ID,
			'post_date'				=> date( 'Y-m-d H:i:s' ),
			'post_date_gmt'			=> date( 'Y-m-d H:i:s' ),
			'post_title'			=> $data['name'],
			'post_excerpt'			=> '',
			'post_status'			=> 'publish',
			'comment_status'		=> 'closed',
			'ping_status'			=> 'closed',
			'post_password'			=> '',
			'post_name'				=> sanitize_title( $data['name'] ),
			'to_ping'				=> '',
			'pinged'				=> '',
			'post_modified'			=> date( 'Y-m-d H:i:s' ),
			'post_modified_gmt'		=> date( 'Y-m-d H:i:s' ),
			'post_content_filtered' => '',
			'post_parent'			=> 0,
			'menu_order'			=> 0,
			'post_type'				=> 'videoplaylist',
			'post_mime_type'		=> '',
			'comment_count'			=> 0,
		);
		
		if ( isset($_GET['id']) ) {
		
			$id = (int) $_GET['id'];
			$wpdb->update( $table, $data, array( 'id' => $id ) );			
			
			$post['ID'] = $data['post_id'];	
			$post['post_content'] = '[yendifgallery playlist=' . $id . ']';
			$post['guid'] = get_site_url() . '/?post_type=videoplaylist&#038;p=' . $data['post_id'];		
			wp_update_post( $post );
			
		} else {
		
			$wpdb->insert( $table,  $data );
			$id = $wpdb->insert_id;
			
			$post['post_content'] = '[yendifgallery playlist=' . $id . ']';
			$post_id = wp_insert_post( $post );
			$wpdb->update( $table, array( 'post_id' => $post_id ), array( 'id' => $id ) );
			wp_update_post( array('ID' => $post_id, 'guid' => get_site_url() . '/?post_type=videoplaylist&#038;p=' . $post_id) );
			
		};	
	
		echo '<script type="text/javascript">window.location = "' . admin_url( 'admin.php?page=yendif-player-playlists' ) . '";</script>';
		exit(); 

	}
	
	/**
	 * Publish or unpublish selected rows.
	 *
	 * @since     1.0.0
	 */
	public function publish( $published ) {
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_playlists';
		$data = array( 'published' => $published );
		
		if( is_array( $_GET['id'] ) ) {
		
			foreach ( $_GET['id'] as $id ) {
				$wpdb->update( $table, $data, array( 'id' => $id ) );
			}
			
		} else {
		
			$wpdb->update( $table, $data, array( 'id' => $_GET['id'] ) );
			
		}	
	
		echo '<script type="text/javascript">window.location = "' . admin_url( 'admin.php?page=yendif-player-playlists' ) . '";</script>';
		exit(); 

	}
	
	/**
	 * Delete selected rows or single playlist item.
	 *
	 * @since     1.0.0
	 */
	public function delete() {
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_playlists';	
		
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
		
		echo '<script type="text/javascript">window.location = "' . admin_url( 'admin.php?page=yendif-player-playlists' ) . '";</script>';
		exit(); 

	}	

}