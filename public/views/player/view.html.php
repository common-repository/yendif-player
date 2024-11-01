<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */

class Yendif_Player_Player_View {

	/**
	 * Holds the page perma structure
	 *
	 * @since    1.2.0
	 *
	 * @var      string
	 */
	private $page_perma_structure;
	
	/**
	 * Instance of the model object.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	private $model = null;
	
	/**
	 * Global configuration data of the player.
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	private $config = null;
	
	/**
	 * Number of players used in current page.
	 *
	 * @since    1.0.0
	 *
	 * @var      int
	 */
	private $players = 0;
	
	/**
	 * List of media types and its commonly accepted properties.
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	private $types = array(
		'video' => array( 'mp4', 'webm', 'ogg', 'captions' ),
		'youtube' => array( 'youtube', 'captions' ),		
		'rtmp' => array( 'rtmp', 'flash', 'mp4', 'captions' ),
		'audio' => array( 'mp3', 'wav', 'ogg' )
	);
	
	/**
	 * List of supported shortcode properties.
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	private $properties = array(
		'autoplay',
		'analytics',
		'autoplaylist',
		'controlbar',
		'currenttime',
		'embed',
		'engine',
		'fullscreen',
		'keyboard',
		'license',
		'logo',
		'loop',
		'playbtn',
		'playlistheight',
		'playlistposition',
		'playlistwidth',
		'playpause',
		'preload',
		'progress',		
		'ratio',
		'responsive',
		'theme',
		'volume',
		'volumebtn'
	);
	
	/**
	 * Script to be hooked in footer.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $script = '';
	
	/**
	 * Constructor of this class.
	 *
	 * @since     1.0.0
	 */
	public function __construct( $model ) {
	
		global $wpdb, $wp_rewrite;
		
		$this->page_perma_structure = $wp_rewrite->get_page_permastruct();
		$this->model = $model;

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_footer', array( $this, 'load_footer' ) );
		
	}	
	
	/**
	 * Load the default layout
	 *
	 * @since     1.0.0
	 */
	public function load_player( $attributes, $config, $players ) {
			
		$this->config = $config;
		$this->players = $players;
			
		$media = 'video';
		$playlist = 0;
		
		// Work for inputs from attributes
		if ( array_key_exists( 'type', $attributes ) ) {
			$media = $attributes['type'];
		} else if ( array_key_exists( 'audio', $attributes ) ) {
			$media = 'audio';
		};
		
		if ( array_key_exists( 'playlist', $attributes ) ) {
			$playlist = 1;
		} else if ( array_key_exists( $media, $attributes ) ) {
			$ids = explode( ',', $attributes[$media] );
			array_filter( $ids );
			if ( count( $ids ) > 1 ) {
				$playlist = 1;
				$attributes['ids'] = $ids;
			};
		} else if ( array_key_exists( 'sort', $attributes ) ) {
			$playlist = 1;
		} else if ( array_key_exists( 'featured', $attributes ) ) {
			$playlist = 1;
		};
		
		$responsive = array_key_exists( 'responsive',  $attributes ) ? $attributes['responsive'] : $this->config['responsive'];
		$width = array_key_exists( 'width',  $attributes ) ? $attributes['width'] : $this->config['width'];
		$height = array_key_exists( 'height',  $attributes ) ? $attributes['height'] : $this->config['height'];
		$style = ( $responsive == 0 ) ? 'style="width:' . $width . 'px; height:' . $height . 'px;" ' : '';
		
		if ( array_key_exists( 'sort', $attributes ) ) {
			$attributes['sort'] = $attributes['sort'];
		} else {
			$attributes['sort'] = $this->config['sort_order'];
		};
		
		if ( array_key_exists( 'title_limit', $attributes ) ) {
			$attributes['title_chars_limit'] = $attributes['title_limit'];
		} else {
			$attributes['title_chars_limit'] = $this->config['title_chars_limit'];
		};		
		
		if ( array_key_exists( 'description', $attributes ) ) {
			$attributes['show_desc'] = $attributes['description'];
		} else {
			$attributes['show_desc'] = $this->config['show_desc'];
		};
		
		if ( array_key_exists( 'description_limit', $attributes ) ) {
			$attributes['desc_chars_limit'] = $attributes['description_limit'];
		} else {
			$attributes['desc_chars_limit'] = $config['desc_chars_limit'];
		};
		
		// Process output
		return ( $playlist === 1 ) ? $this->playlist( $attributes, $media, $style ) : $this->single( $attributes, $media, $style );
	}
	
	/**
	 * Load single media player.
	 *
	 * @since    1.0.0
	 * 
	 * @return      string		Text or HTML that holds the player
	 */
	private function single( $attributes, $media, $style ) {
		
		$sources = '';
		$data_attrs = '';
		
		if ( array_key_exists( $media, $attributes ) ) {
			global $wpdb;
		
			$table = $wpdb->prefix . 'yendif_player_media';
			$sql = "SELECT * FROM $table";
			$sql .= ( $media == 'audio' ) ? " WHERE type = 'audio'" : " WHERE type != 'audio'";
			$sql .= " AND published=1";
			
			if ( array_key_exists( 'featured',  $attributes ) ) {
			 	$sql .= ' AND featured=' . (int) $attributes['featured'];
			};
		
			$_media = $attributes[$media];			
			if ( $_media == 'latest' ) {
				$sql .= " ORDER BY id DESC LIMIT 1";
			} else if ( $_media == 'popular' ) {
				$sql .= " ORDER BY views DESC LIMIT 1";
			} else if ( $_media == 'random' ) {
				$sql .= " ORDER BY RAND() LIMIT 1";
			} else {
				$sql .= " AND id=" . (int) $_media;
			};			
				
			if ( $row = $wpdb->get_row( $sql, ARRAY_A ) ) {
				$type = $row['type'];	
				$types = $this->types[$type];
				$attributes = array_merge( $attributes, $row );
			} else {
				return '';
			};
			
			if( $media == 'audio' ) $data_attrs .= "data-embed=0 data-share=0 data-download=0";
		} else {
			$types = ( $media == 'video' ) ? array_merge( $this->types['video'], $this->types['youtube'], $this->types['rtmp'] ) : $this->types['audio'];
			$types = array_unique( $types );
			$data_attrs .= "data-embed=0";
		};
		
		$data_attrs .= " data-share=0 data-download=0";		
		
		$types = array_values( $types );
		$count = count( $types );
		for ( $i = 0; $i < $count; $i++ ) {
			$type = $types[$i];
			if ( array_key_exists( $type, $attributes ) && ! empty( $attributes[$type] ) ) {
				$src = $attributes[$type];
				switch ( $type ) {
					case 'mp4' :
						$filetype = wp_check_filetype( $src );
						$mimetype = ( $filetype['ext'] == 'm3u8' ) ? 'application/x-mpegurl' : ( $filetype['ext'] == 'flv' ? 'video/flash' : 'video/mp4' );
						$sources .= '<source type="' . $mimetype . '" src="' . $src . '">';
						break;					
					case 'rtmp' :
						$sources .= '<source type="video/flash" src="' . $attributes['flash'] . '" data-rtmp="' . $src . '">';
						unset( $attributes['flash'] );
						break;
					case 'mp3' :
						$sources .= '<source type="' . $media . '/mpeg" src="' . $src . '">';
						break;
					case 'captions' :
						$sources .= '<track src="' . $src . '">';
						break;
					default :
						$sources .= '<source type="' . $media . '/' . $type . '" src="' . $src . '">';
				};
			};
		};
		
		$properties = $this->properties;		
		array_push( $properties, 'poster' );
		$count = count( $properties );
		for ( $i = 0; $i < $count; $i++ ) {
			$key = $properties[$i];
			if ( array_key_exists( $key,  $attributes ) ) {
				if ( $key == 'volume' )	$attributes[$key]  = $attributes[$key] / 100;
				$data_attrs .= " data-" . $key . '="' . $attributes[$key] . '"';
			};				
		};
		
		if ( array_key_exists( 'id',  $attributes ) ) {
			$data_attrs .= ' data-vid="' . $attributes['id'] . '"';
		};
		
		$html = '<div ' . $style . 'class="yendifplayer"' . $data_attrs . '><' . $media . '>' . $sources . '</' . $media . '></div>';
		
		if ( array_key_exists( 'post_id', $attributes ) ) {
		
			$post = get_post();
			if ( $post->post_type == 'videopage' && $post->ID == $attributes['post_id'] ) {
				$html .= '<div class="yendif-video-page-info">';				
				
				if ( $this->config['show_views'] ) {
					$html .= '<div class="yendif-views">' . $attributes['views'] . ' ' . __('views', YENDIF_PLAYER_PLUGIN_SLUG) . '</div>';
				};
				
				if ( $this->config['show_desc'] ) {
					$html .= '<div class="yendif-description">' . $attributes['description'] . '</div>';
				};
				
				$playlists = explode( ' ', trim($attributes['playlists']) );
				$playlists = array_filter( $playlists );
				if ( count($playlists) ) {
					$html .= '<span class="meta-nav">' . __('Playlists', YENDIF_PLAYER_PLUGIN_SLUG) . ' : </span>';
					$playlist_nav = array();
					foreach ( $playlists as $playlist ) {
						$playlist_data = $this->get_playlist_data( (int) $playlist );
						$playlist_nav[] = '<a href="'.$playlist_data['permalink'].'" class="yendif-playlist-link">' . $playlist_data['name'] . '</a>';
					};
					$html .= count($playlist_nav) ? implode(', ', $playlist_nav) : '';
				};
				$html .= '</div>';
			};
				
		};
		
		return $html;		
		
	}
	
	/**
	 * Load player with playlist.
	 *
	 * @since    1.0.0
	 *
	 * @return      string		Text or HTML that holds the player
	 */
	private function playlist( $attributes, $media, $style ) {				
		
		global $wpdb;
		$table = $wpdb->prefix . 'yendif_player_media';
		$sql = "SELECT * FROM $table";		
		$sql .= ( $media == 'audio' ) ? " WHERE type = 'audio'" : " WHERE type != 'audio'";
		$sql .= " AND published = 1";
		
		if ( array_key_exists( 'featured',  $attributes ) ) {
			 $sql .= ' AND featured=' . (int) $attributes['featured'];
		};
		
		if ( array_key_exists( 'ids',  $attributes ) ) {
			$ids = implode( ',', $attributes['ids'] );			
			$sql .= " AND id IN (" . $ids . ") ORDER BY FIELD (id," . $ids . ")";	
		} else {			
			if ( array_key_exists( 'playlist',  $attributes ) ) {
				$ids = $attributes['playlist'];
				$ids = explode( ',', $ids );
				$likes = array();				
				foreach ( $ids as $id ) {
					$likes[] = " playlists LIKE '% " . $id . " %'";
				};
				$sql .= ( count( $likes ) ? ' AND ('. implode( ' OR ', $likes ) . ')' : '' );		 
			};
			
			if ( array_key_exists( 'sort',  $attributes ) ) {
				switch ( $attributes['sort'] ) {
		 			case 'latest'     :	$sql .= ' ORDER BY id DESC'; break;
					case 'popular'    :	$sql .= ' ORDER BY views DESC'; break;
					case 'date_added' :	$sql .= ' ORDER BY id ASC';	break;
					case 'a_z'        : $sql .= ' ORDER BY title ASC';	break;
					case 'z_a'        : $sql .= ' ORDER BY title DESC'; break;
					case 'random'     :	$sql .= ' ORDER BY RAND()';	break;
		 		};
			};
			
			if ( array_key_exists( 'limit',  $attributes ) ) {
				$sql .= ' LIMIT ' . (int) $attributes['limit'];
			};			
		};
		
		$items = $wpdb->get_results( $sql );		
		$obj = ''; $index = 0;
		foreach ( $items as $item ) {			
			$types = $this->types[$item->type];
			array_push( $types, 'poster', 'title', 'description', 'duration', 'id' );
			$count = count( $types );			
			for ( $i = 0; $i < $count; $i++ ) {					
				$type = $types[$i];
				if ( $src = $item->$type ) {
				
					switch($type) {						
						case 'id' :
							$type = 'vid';
							break;
						case 'mp4' :
							$filetype = wp_check_filetype( $src );
							$type = ( $filetype['ext'] == 'm3u8' ) ? 'mpegurl' : ( $filetype['ext'] == 'flv' ? 'flash' : 'mp4' );
							break;
						case 'title' :
							$src = Yendif_Player_Functions::Truncate( $src, $attributes['title_chars_limit'] );
							break;
						case 'description' :
							if ( $attributes['show_desc'] ) {
								$src = Yendif_Player_Functions::Truncate( $src, $attributes['desc_chars_limit'] );
							};
							break;
					};
					
					$obj[$media.'s'][$index][$type] = $src;
				};			
			};
			
			++$index;
		};
		
		$count = count( $this->properties );
		for ( $i = 0; $i < $count; $i++ ) {
			$key = $this->properties[$i];
			if ( array_key_exists( $key,  $attributes) ) {
				switch ( $key ) {
					case 'playlistwidth' : $obj['playlistWidth'] = $attributes[$key]; break;
					case 'playlistheight' :	$obj['playlistHeight'] = $attributes[$key];	break;
					case 'playlistposition' : $obj['playlistPosition'] = $attributes[$key];	break;
					case 'volume' : $obj[$key] = $attributes[$key] / 100; break;
					default : $obj[$key] = $attributes[$key];
				};				
			};				
		};
		
		if( $media == 'audio' ) {
			$obj['embed'] = 0;
		}
		
		$obj['share'] = 0;
		$obj['download'] = 0;
		
		$uid = uniqid('yendif' . $this->players);
		if ( $obj ) $this->script .= '$("#' . $uid . '").yendifplayer(' . json_encode( $obj ) . ');';		
		return '<div ' . $style . 'id="' . $uid . '"></div>';
		
	}
	
	/**
	 * Get playist name and permalink
	 *
	 * @since     1.2.0
	 *
	 * @return      array		An associative array containing playlist name and permalink
	 */
	private function get_playlist_data( $playlist_id ) {
		
		global $wpdb;
	
		$table = $wpdb->prefix . 'yendif_player_playlists';	
		$item = $wpdb->get_row( "select name, post_id from $table WHERE id=" . $playlist_id );
		
		return array( 'name' => $item->name, 'permalink' => get_permalink( $item->post_id ) );
		
	}
	
	/**
	 * Print playlist scripts in the footer.
	 *
	 * @since    1.0.0
	 */
	public function load_footer( $attributes ) { ?>		
		
	<script type="text/javascript">
	
	  (function ( $ ) {
	
	    "use strict";

	    $(function () {
			
	      <?php echo $this->script; ?>		  
		  
	    });

	  }(jQuery));
	  
	</script>
		
	<?php }

}