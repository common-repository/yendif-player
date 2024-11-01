<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */
 
class Yendif_Player_Settings_Model {

	/**
	 * List of available column names in "yendif_player_settings" table.
	 *
	 * @since    1.0.0
	 *
	 * @var      Array
	 */
	private $columns = array(
		'id',
		'analytics',
		'autoplay',
		'autoplaylist',
		'controlbar',
		'currenttime',
		'duration',
		'desc_chars_limit',
		'download',
		'embed',
        'engine',
		'fullscreen',
		'keyboard',
		'license',
		'logo',
		'loop',
		'no_of_cols',
		'no_of_rows',
		'playbtn',
		'playlist_height',
		'playlist_position',
		'playlist_width',
		'playpause',					
		'progress',
		'ratio',
        'responsive',
		'share',
		'show_desc',
		'show_views',
		'sort_order',				
		'theme',
		'thumb_height',
		'thumb_width',
		'title_chars_limit',		
		'volume',					
		'volumebtn',
		'width',
		'height'
	);

	/**
	 * Constructor of this class.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

	}
	
	/**
	 * Load data from "yendif_player_settings" table WHERE id = 1.
	 *
	 * @since     1.0.0
	 */
	public function item() {
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_settings';
		$sql = "SELECT * FROM $table WHERE id = %d";		
		$row = $wpdb->get_row( $wpdb->prepare( $sql, 1 ) );
		
		return $row;

	}
	
	/**
	 * Update "yendif_player_settings" table with new data.
	 *
	 * @since     1.0.0
	 */
	public function save() {
	
		if ( ! check_admin_referer( 'yendif-player-nonce' ) ) return;
	
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_settings';
		$data = array();
		foreach ( $_POST as $k => $v ) { 
  			if ( in_array( $k, $this->columns) ) {
				$data[$k] = trim( $v );
  			} 
		}
		
		$data['volume'] = max( 0, (int) $data['volume'] );
		$data['volume'] = min( 100, (int) $data['volume'] );
		
		$wpdb->update( $table,  $data, array( 'id' => 1 ) );
	
		echo '<script type="text/javascript">window.location = "' . admin_url( 'admin.php?page=yendif-player-settings') . '";</script>';
		exit(); 

	}

}