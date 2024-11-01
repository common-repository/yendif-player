<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */

class Yendif_Player_Gallery_Model {

	/**
	 * Constructor of this class.
	 *
	 * @since     1.2.0
	 */
	public function __construct() {
	
	}
	
	/**
	 * Get yendif_player_playlists table data
	 *
	 * @since     1.2.0
	 *
	 * @return		object		An object containing playlist data
	 */
	public function getPlaylists( $attributes ) {
	
		global $wpdb;
	  
	    $table = $wpdb->prefix . 'yendif_player_playlists';
						
		$sql  = "SELECT * FROM $table WHERE published=1";			
					
		switch ( $attributes['sort'] ) {
		 	case 'latest'     :	$sql .= ' ORDER BY id DESC'; break;				
			case 'date_added' :	$sql .= ' ORDER BY id ASC'; break;
			case 'a_z'        : $sql .= ' ORDER BY name ASC'; break;
			case 'z_a'        : $sql .= ' ORDER BY name DESC'; break;
			case 'random'     :	$sql .= ' ORDER BY RAND()'; break;
		};
			
		if ( array_key_exists( 'limit',  $attributes ) ) {
			$sql .= ' LIMIT ' . (int) $attributes['limit'];
		};		
							
		$items = $wpdb->get_results( $sql );
			
	    return $items;
		 
	}
	
	/**
	 * Get yendif_player_videos table data
	 *
	 * @since     1.2.0
	 *
	 * @return		object		An object containing videos data
	 */
	public function getVideos( $attributes ) {
		
		global $wpdb;
					
		$table = $wpdb->prefix . 'yendif_player_media';		
		
		$sql  = "SELECT * FROM $table WHERE published=1 AND type!='audio'";	
		
		if ( array_key_exists('video', $attributes) ) {
		    $sql .= ' AND id IN (' . $attributes['video'] . ')';
			$attributes['sort'] = 'custom';
	    };
			
		if ( array_key_exists('playlist', $attributes) ) {
			$playlists = explode(',', $attributes['playlist']);
			$likes = array();
			foreach($playlists as $playlist) {
		    	$likes[] = " playlists LIKE '% " . (int) $playlist ." %'";					
			};
			$sql .= (count($likes)) ? ' AND (' . implode(' OR ', $likes) . ')' : '';
		};	
											
											
		if ( array_key_exists( 'featured',  $attributes ) ) {
			 $sql .= ' AND featured=' . $attributes['featured'];
		}
		
		switch ( $attributes['sort'] ) {
			case 'latest'     :	$sql .= ' ORDER BY id DESC'; break;
			case 'popular'    :	$sql .= ' ORDER BY views DESC'; break;
			case 'date_added' :	$sql .= ' ORDER BY id ASC'; break;
			case 'a_z'        : $sql .= ' ORDER BY title ASC'; break;
			case 'z_a'        : $sql .= ' ORDER BY title DESC'; break;
			case 'random'     :	$sql .= ' ORDER BY RAND()'; break;
			case 'custom'     : $sql .= " ORDER BY FIELD (id," . $attributes['video'] . ")"; break;
		};	
				
		if ( array_key_exists( 'limit',  $attributes ) ) {
			$sql .= ' LIMIT ' . (int) $attributes['limit'];
		};
		
	     $items = $wpdb->get_results( $sql );	
		 	 
		 return $items;
		 
	 }	

}