<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */

class Yendif_Player_Gallery_View {

	/**
	 * Instance of the model object.
	 *
	 * @since    1.2.0
	 *
	 * @var      object
	 */
	private $model = null;
	
	/**
	 * Holds the page perma structure
	 *
	 * @since    1.2.0
	 *
	 * @var      string
	 */
	private $page_perma_structure;
	
	/**
	 * Global configuration data of the player.
	 *
	 * @since    1.2.0
	 *
	 * @var      array
	 */
	private $config = null;
	
	/**
	 * Constructor of this class.
	 *
	 * @since     1.2.0
	 */
	public function __construct( $model ) {
	
		global $wpdb, $wp_rewrite;
		
		$this->model = $model;		
		$this->page_perma_structure = $wp_rewrite->get_page_permastruct();
		
	}	
	
	/**
	 * Load the appropriate gallery layout
	 *
	 * @since     1.2.0
	 */
	public function load_gallery( $attributes, $config ) {
			
		$this->config = $config;
		
		$type = 'videos';
		
		// Work for inputs from attributes
		if ( array_key_exists( 'type', $attributes ) ) {
			$type = $attributes['type'];
		};		
		
		if ( array_key_exists( 'sort', $attributes ) ) {
			$attributes['sort'] = $attributes['sort'];
		} else {
			$attributes['sort'] = $config['sort_order'];
		};
		
		if ( array_key_exists( 'thumb_width', $attributes ) ) {
			$attributes['width'] = $attributes['thumb_width'];
		} else {
			$attributes['width'] = $config['thumb_width'];
		};		
		
		if ( array_key_exists( 'thumb_height', $attributes ) ) {
			$attributes['height'] = $attributes['thumb_height'];
		} else {
			$attributes['height'] = $config['thumb_height'];
		};		
		
		if ( array_key_exists( 'columns', $attributes ) ) {
			$attributes['columns'] = $attributes['columns'];
		} else {
			$attributes['columns'] = $config['no_of_cols'];
		};		
		
		if ( array_key_exists( 'rows', $attributes ) ) {
			$attributes['rows'] = $attributes['rows'];
		} else {
			$attributes['rows'] = $config['no_of_rows'];
		};
		
		$attributes['responsive_class'] = '';
		if ( $this->config['responsive'] == 1 ) {
			$attributes['responsive_class'] = 'yendif-responsive';
		};		
		
		if ( array_key_exists( 'title_limit', $attributes ) ) {
			$attributes['title_chars_limit'] = $attributes['title_limit'];
		} else {
			$attributes['title_chars_limit'] = $config['title_chars_limit'];
		};		
		
		if ( array_key_exists( 'description', $attributes ) ) {
			$attributes['show_desc'] = $attributes['description'];
		} else {
			$attributes['show_desc'] = $config['show_desc'];
		};		
		
		if ( array_key_exists( 'description_limit', $attributes ) ) {
			$attributes['desc_chars_limit'] = $attributes['description_limit'];
		} else {
			$attributes['desc_chars_limit'] = $config['desc_chars_limit'];
		};		
		
		if ( array_key_exists( 'views', $attributes ) ) {
			$attributes['show_views'] = $attributes['views'];
		} else {
			$attributes['show_views'] = $config['show_views'];
		};		
		
		// Process output
		return ( $type === 'playlist' ) ? $this->load_playlists( $attributes ) : $this->load_videos( $attributes );
	}
	
	/**
	 * Load the playlists layout
	 *
	 * @since     1.2.0
	 */
	public function load_playlists( $attributes ) {

		$items = $this->model->getPlaylists( $attributes );
		
		$html = '';
		
		$html .= '<div class="yendif-video-gallery ' . $attributes['responsive_class'] . '">';
  		$html .= '<div class="yendif-gallery">';
		
		$count = count($items);
		if ( $count == 0 ) return '';
					
		$limit = $attributes['rows'] * $attributes['columns'];		 
		if ( $count < $limit ) {
			$limit = $count;
		};			
		  
		$start = isset( $_GET['start'] ) ? (int) $_GET['start'] : 1;
		$limitstart = ($start - 1) * $limit;
		$limitend   = $start * $limit;
	    if($count < $limitend) $limitend = $count;
		$column = 0;
		
  	  	for ( $i = $limitstart; $i < $limitend; $i++ ) {
			$item = $items[$i];
		
    		if( $column >= $attributes['columns'] ) {
				$html .= '<div class="yendif-clear"></div>';
				$column = 0;
				$row++;		
			}
		
			$column++;
    		$target = $this->get_playlist_permalink( $item );
			if ( ! $item->image ) {
				$item->image = YENDIF_PLAYER_PLUGIN_URL . '/public/assets/images/placeholder.jpg';
			}
			
    		$html .= '<a class="yendif-item" style="width:' . $attributes['width'] . 'px;" href="' . $target . '">';
  	  		$html .= '<span class="yendif-item-wrapper">';
        	$html .= '<span class="yendif-thumb" style="width:' . $attributes['width'] . 'px; height:' . $attributes['height'] . 'px;">';   	  
    	  	$html .= '<img class="yendif-thumb-clip" src="' . $item->image . '"  alt="' . $item->name . '" title="' . $item->name . '" />';
    		$html .= '</span>';
    		$html .= '<span class="yendif-title">' . Yendif_Player_Functions::Truncate( $item->name, $attributes['title_chars_limit'] ) . '</span>';
			$html .= '</span>';
   			$html .= '</a>';
    	};
    	$html .= '</div>';
    	$html .= '<div class="yendif-clear-always"></div>';
    	$html .= '<div class="yendif-pagination">';
		$args = array(
    	    'base'      => @add_query_arg('start', '%#%'),
    		'format'    => '',    		
    		'end_size'  => 1,
			'total'     => ceil( $count / $limit ),
    		'current'   => $start,
    		'prev_text' => __('prev', YENDIF_PLAYER_PLUGIN_SLUG),
    		'next_text' => __('next', YENDIF_PLAYER_PLUGIN_SLUG),
			'type'      => 'list'
		);		
		$html .= paginate_links( $args );
		$html .= '<div class="yendif-clear-always"></div>';	
		$html .= '</div>';
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	 * Load the videos layout
	 *
	 * @since     1.2.0
	 */
	public function load_videos( $attributes ) {

		global $wpdb;
		
		$items = $this->model->getVideos( $attributes );
		
		$html = '';
				
		$html .= '<div class="yendif-video-gallery ' . $attributes['responsive_class'] . '">';
  		$html .= '<div class="yendif-gallery">';
		
		$count = count($items);
		if ( $count == 0 ) return '';
					
		$limit = $attributes['rows'] * $attributes['columns'];		 
		if ( $count < $limit ) { 
			$limit = $count;
		};			
		  
		$start = isset( $_GET['start'] ) ? (int) $_GET['start'] : 1;
		$limitstart = ($start - 1) * $limit;
		$limitend   = $start * $limit;
	    if($count < $limitend) $limitend = $count;
		$column = 0;
		
  	  	for ( $i = $limitstart; $i < $limitend; $i++ ) {
			$item = $items[$i];
		
    		if( $column >= $attributes['columns'] ) {
				$html .= '<div class="yendif-clear"></div>';
				$column = 0;
				$row++;		
			}
		
			$column++;
    		$target = $this->get_video_permalink( $item );
			if ( ! $item->poster ) {
				$item->poster = YENDIF_PLAYER_PLUGIN_URL . '/public/assets/images/placeholder.jpg';
			}
			
    		$html .= '<a class="yendif-item" style="width:' . $attributes['width'] . 'px;" href="' . $target . '">';
  	  		$html .= '<span class="yendif-item-wrapper">';
        	$html .= '<span class="yendif-thumb" style="width:' . $attributes['width'] . 'px; height:' . $attributes['height'] . 'px;">';   	  
    	  	$html .= '<img class="yendif-thumb-clip" src="' . $item->poster . '"  alt="' . $item->title . '" title="' . $item->title . '" />';
          	$html .= '<img class="yendif-thumb-overlay" src="' . YENDIF_PLAYER_PLUGIN_URL . '/public/assets/images/play.png" alt="' . $item->title . '" />';
          	if( ! empty($item->duration) ) {
          		$html .= '<span class="yendif-duration">' . $item->duration . '</span>';
			};
    		$html .= '</span>';
			$html .= '<span class="yendif-item-info">';
    		$html .= '<span class="yendif-title">' . Yendif_Player_Functions::Truncate( $item->title, $attributes['title_chars_limit'] ) . '</span>';
			if ( $attributes['show_desc'] ) {
				$html .= '<span class="yendif-description">' . Yendif_Player_Functions::Truncate( $item->description, $attributes['desc_chars_limit'] ) . '</span>';
			}
			if ( $attributes['show_views'] ) {
				$html .= '<span class="yendif-views">' . $item->views . ' ' . __('views', YENDIF_PLAYER_PLUGIN_SLUG) . '</span>';
			}
			$html .= '</span>';
			$html .= '</span>';
   			$html .= '</a>';
    	};
    	$html .= '</div>';
    	$html .= '<div class="yendif-clear-always"></div>';
    	$html .= '<div class="yendif-pagination">';
		$args = array(
    	    'base'      => @add_query_arg('start', '%#%'),
    		'format'    => '',    		
    		'end_size'  => 1,
			'total'     => ceil( $count / $limit ),
    		'current'   => $start,
    		'prev_text' => __('prev', YENDIF_PLAYER_PLUGIN_SLUG),
    		'next_text' => __('next', YENDIF_PLAYER_PLUGIN_SLUG),
			'type'      => 'list'
		);		
		$html .= paginate_links( $args );
		$html .= '<div class="yendif-clear-always"></div>';
		$html .= '</div>';
		$html .= '</div>';
		
		return $html;
		
	}
	
	/**
	 * Build playlist permalink
	 *
	 * @since     1.2.0
	 *
	 * @return      string		Playlist Page URL
	 */
	private function get_playlist_permalink( $playlist ) {
		
		return get_permalink( $playlist->post_id );
		
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