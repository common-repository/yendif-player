<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */ 
 
class Yendif_Related_Videos {

	/**
	 * Holds the active post data
	 *
	 * @since    1.2.0
	 *
	 * @var      object
	 */
	private $active_post;
	
	/**
	 * Holds the active video id
	 *
	 * @since    1.2.0
	 *
	 * @var      int
	 */
	private $active_video_id = 0;
	
	/**
	 * Holds the page perma structure
	 *
	 * @since    1.2.0
	 *
	 * @var      string
	 */
	private $page_perma_structure;
	
	/**
	 * Constructor of this class.
	 *
	 * @since     1.2.0
	 */
	public function __construct( $post ) {
	
		$this->active_post = $post;
	
	}
  
	/**
	 * Outputs the content of the widget
	 *
	 * @since     1.2.0
	 *
	 * @return      string		Text or HTML that holds the gallery
	 */
	public function build_gallery( $attributes ) {
  
		global $wpdb, $wp_rewrite;
					
		$this->page_perma_structure = $wp_rewrite->get_page_permastruct();
		
		$table = $wpdb->prefix . 'yendif_player_media';		
		
		$sql = "SELECT * FROM $table WHERE published=1 AND type!='audio'";	
		
		$playlist_ids = $this->get_related();		
		$_ids = array();
		foreach ( $playlist_ids as $playlist_id ) {
			$_ids[] = "playlists LIKE '% " . $playlist_id . " %'";
		}
		$sql .= count($_ids) ? ' AND ('. implode(' OR ', $_ids) . ')' : '';	 
		
		if ( $this->active_video_id > 0 ) {
			$sql .=  " AND id!=" . $this->active_video_id;
		}
		
		switch( $attributes['sort'] ) {
		 	case 'latest'     : $sql .= ' ORDER BY id DESC'; break;
			case 'popular'    :	$sql .= ' ORDER BY views DESC'; break;
			case 'date_added' : $sql .= ' ORDER BY id ASC'; break;			
			case 'a_z'        : $sql .= ' ORDER BY title ASC'; break;
			case 'z_a'        : $sql .= ' ORDER BY title DESC'; break;
			case 'random'     : $sql .= ' ORDER BY RAND()'; break;
		};
		
		$sql .=  " LIMIT " . $attributes['limit'];
		
		$items = $wpdb->get_results( $sql );
		
		$count = count($items);
		if ( !$count ) return false;
		
		$html = '<div class="yendif-video-gallery yendif-widget">';
		$html .= '<div class="yendif-gallery">';
  	  	for ( $i = 0; $i < $count; $i++ ) {
			$item = $items[$i];
			
    		$target = $this->get_video_permalink( $item );
			if ( ! $item->poster ) {
				$item->poster = YENDIF_PLAYER_PLUGIN_URL . '/public/assets/images/placeholder.jpg';
			}
			
    		$html .= '<a class="yendif-item" style="width:100%;" href="' . $target . '">';
  	  		$html .= '<span class="yendif-item-wrapper">';
        	$html .= '<span class="yendif-thumb yendif-left" style="width:' . $attributes['thumb_width'] . 'px; height:' . $attributes['thumb_height'] . 'px;">';   	  
    	  	$html .= '<img class="yendif-thumb-clip" src="' . $item->poster . '"  alt="' . $item->title . '" title="' . $item->title . '" />';
          	$html .= '<img class="yendif-thumb-overlay" src="' . YENDIF_PLAYER_PLUGIN_URL . '/public/assets/images/play.png" alt="' . $item->title . '" />';
          	if( ! empty($item->duration) ) {
          		$html .= '<span class="yendif-duration">' . $item->duration . '</span>';
			};
    		$html .= '</span>';
			$html .= '<span class="yendif-item-info">';
    		$html .= '<span class="yendif-title">' . Yendif_Player_Functions::Truncate( $item->title, $attributes['title_chars_limit'] ) . '</span>';			
			if ( $attributes['show_views'] ) {
				$html .= '<span class="yendif-views">' . $item->views . ' '.__('views', YENDIF_PLAYER_PLUGIN_SLUG) . '</span>';
			}
			if ( $attributes['show_desc'] ) {
				$html .= '<span class="yendif-description">' . Yendif_Player_Functions::Truncate( $item->description, $attributes['desc_chars_limit'] ) . '</span>';
			}
			$html .= '</span>';
			$html .= '<span class="yendif-clear-always"></span>';
			$html .= '</span>';
   			$html .= '</a>';
    	};
    	$html .= '</div>';
		$html .= '</div>';
		
		return $html;
					 
  	}  
 
 	/**
	 * Get playlist ID(s) related to the current playing video and also updates the active video ID
	 *
	 * @since     1.2.0
	 *
	 * @return      array		Array of playlist ID(s) related to the current playing video
	 */
	private function get_related() {
	
		global $wpdb;
		
		if ( $this->active_post->post_type == 'videopage' ) {
		
			$table = $wpdb->prefix . 'yendif_player_media';
			$video = $wpdb->get_row( "select id, playlists from $table WHERE post_id=" . $this->active_post->ID );
			
			$this->active_video_id = $video->id;
			return explode( ' ', trim($video->playlists) );
			
		} else if ( $this->active_post->post_type == 'videoplaylist' ) {
		
			$table = $wpdb->prefix . 'yendif_player_playlists';
			$playlist_id = $wpdb->get_var( "select id from $table WHERE post_id=" . $this->active_post->ID );
			
			return array($playlist_id);
			
		};

		return array(0);
		
	}
	
  	/**
	 * Build video permalink
	 *
	 * @since     1.2.0
	 *
	 * @return      string		Video Page URL
	 */
	private function get_video_permalink( $video ) {
	
		return get_permalink( $video->post_id );
		
	}
  
} 