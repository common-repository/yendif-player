<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */

class Yendif_Player_Controller {
	
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
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		
		// Load global configuration data of the plugin
		$this->config = $this->load_config();
		
		// Load "yendifplayer" or "yendifgallery" when appropriate shortcode is found.
		add_shortcode( 'yendifplayer', array( $this, 'load_yendif_player' ) );
		add_shortcode( 'yendifgallery', array( $this, 'load_yendif_gallery' ) );
		
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		
		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );	
		
		// ...
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
		add_action( 'parse_request', array( $this, 'parse_request' ) );	

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
	 * Load global configuration data of the player.
	 *
	 * @since	1.0.0
	 *
	 * @return		array		An associative array containing global configuration data
	 */
	public function load_config() {
		
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_settings';
		$sql = "SELECT * FROM $table WHERE id = %d";		
		$config = $wpdb->get_row( $wpdb->prepare( $sql, 1 ), ARRAY_A );
		
		return $config;
		
	}
	
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = YENDIF_PLAYER_PLUGIN_SLUG;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, YENDIF_PLAYER_PLUGIN_NAME . '/languages/' );

	}
	
	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		$domain = YENDIF_PLAYER_PLUGIN_SLUG;
		
		wp_enqueue_style( $domain . '-plugin-player-styles', YENDIF_PLAYER_PLUGIN_URL . '/public/assets/libraries/yendifplayer.css', array(), YENDIF_PLAYER_VERSION_NUM );
		wp_enqueue_style( $domain . '-plugin-dashicon-styles', get_stylesheet_uri(), array( 'dashicons' ), YENDIF_PLAYER_VERSION_NUM );
		wp_enqueue_style( $domain . '-plugin-gallery-styles', YENDIF_PLAYER_PLUGIN_URL . '/public/assets/css/gallery.css', array(), YENDIF_PLAYER_VERSION_NUM );
		
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
	
		$domain = YENDIF_PLAYER_PLUGIN_SLUG;
		
		wp_enqueue_script( $domain . '-plugin-script', YENDIF_PLAYER_PLUGIN_URL . '/public/assets/libraries/yendifplayer.js', array('jquery'), YENDIF_PLAYER_VERSION_NUM );		
		wp_enqueue_script( $domain . '-plugin-dyn-script', YENDIF_PLAYER_PLUGIN_URL . '/public/assets/js/config.js', array('jquery'), YENDIF_PLAYER_VERSION_NUM );
		
		$config = $this->config;
		$config['playlistWidth'] = $config['playlist_width'];
		$config['playlistHeight'] = $config['playlist_height'];
		$config['playlistPosition'] = $config['playlist_position'];
		$config['volume'] = $config['volume'] / 100;
		$config['share'] = 0;
		$config['download'] = 0;
		$config['swf'] = YENDIF_PLAYER_PLUGIN_URL .  '/public/assets/libraries/player.swf';
		$config['site_url'] = get_option('siteurl');
		$config['page_title'] = get_the_title();
		if ( strpos( $config['site_url'], 'https://' ) !== false ) {
			$config['swf'] = str_replace( 'http://', 'https://', $config['swf'] );
		};
		unset( $config['id'], $config['width'], $config['height'], $config['playlist_width'], $config['playlist_height'],  $config['playlist_position'] );
		wp_localize_script( $domain . '-plugin-dyn-script', 'yendifplayer_config', $config );
		
	}

	/**
	 * Outputs the short code for this object.
	 *
	 * @since    1.0.0
	 * 
	 * @return      string		Text or HTML that holds the player
	 */
	public function load_yendif_player( $attributes ) {
		
		++$this->players;
		
		// Initialize the model		
		include_once( YENDIF_PLAYER_PLUGIN_DIR . 'public/models/player.php' );		
		$model = new Yendif_Player_Player_Model();
		
		// Initialize the view		
		include_once( YENDIF_PLAYER_PLUGIN_DIR . 'public/views/player/view.html.php' );		
		$view = new Yendif_Player_Player_View( $model );
		$attributes = (array) $attributes;
		if( ! count($attributes) ) $attributes['sort'] = 'latest';
		$player = $view->load_player( $attributes, $this->config, $this->players );
		
		return $player;
				
	}
	
	/**
	 * Outputs the short code for this object.
	 *
	 * @since    1.0.0
	 * 
	 * @return      string		Text or HTML that holds the gallery
	 */
	public function load_yendif_gallery( $attributes ) {
		
		// Initialize the model		
		include_once( YENDIF_PLAYER_PLUGIN_DIR . 'public/models/gallery.php' );		
		$model = new Yendif_Player_Gallery_Model();
		
		// Initialize the view		
		include_once( YENDIF_PLAYER_PLUGIN_DIR . 'public/views/gallery/view.html.php' );		
		$view = new Yendif_Player_Gallery_View( $model );
		$gallery = $view->load_gallery( (array) $attributes, $this->config );
		
		return $gallery;
				
	}
	
	/**
	 * Registers query vars.
	 *
	 * @since    2.3.0
	 */
	public function query_vars( $vars ) {
	
		$vars = array_merge( array('embed', 'dl'), $vars );		
    	return $vars;
		
	}
	
	/**
	 * Parse request.
	 *
	 * @since    2.3.0
	 */
	public function parse_request( $wp ) {	
		
    	if( array_key_exists( 'embed', $wp->query_vars ) ) {		
			$this->load_embed_player( (int) $wp->query_vars['embed'] );
    	} else if( array_key_exists( 'dl', $wp->query_vars ) ) {		
			$this->download( (int) $wp->query_vars['dl'] );			
    	}
		
	}
	
	/**
	 * Load Embed Player.
	 *
	 * @since    2.3.0
	 */
	public function load_embed_player( $id ) {
	
		// Build configuration data
		$config = $this->config;
		$config['playlistWidth'] = $config['playlist_width'];
		$config['playlistHeight'] = $config['playlist_height'];
		$config['playlistPosition'] = $config['playlist_position'];
		$config['volume'] = $config['volume'] / 100;
		$config['share'] = 0;
		$config['download'] = 0;
		$config['swf'] = YENDIF_PLAYER_PLUGIN_URL .  '/public/assets/libraries/player.swf';
		$config['site_url'] = get_option('siteurl');
		$config['page_title'] = get_the_title();
		if ( strpos( $config['site_url'], 'https://' ) !== false ) {
			$config['swf'] = str_replace( 'http://', 'https://', $config['swf'] );
		};
		unset( $config['id'], $config['width'], $config['height'], $config['playlist_width'], $config['playlist_height'],  $config['playlist_position'] );
		
		// Build video sources
		global $wpdb;
		
		$types = array(
			'video' => array( 'mp4', 'webm', 'ogg', 'captions' ),
			'youtube' => array( 'youtube', 'captions' ),		
			'rtmp' => array( 'rtmp', 'flash', 'mp4', 'captions' )
		);
	
		$video_element = '';
		
		$table = $wpdb->prefix . 'yendif_player_media';
		$sql = "SELECT * FROM $table WHERE type != 'audio' AND published=1 AND id=" . $id;				
		if( $row = $wpdb->get_row( $sql, ARRAY_A ) ) {
			$sources = '';
			$types = array_values( $types[ $row['type'] ] );
			$count = count( $types );
		
			for( $i = 0; $i < $count; $i++ ) {
				$type = $types[$i];
				if( array_key_exists( $type, $row ) && ! empty( $row[$type] ) ) {
					$src = $row[$type];
					switch( $type ) {
						case 'mp4' :
							$filetype = wp_check_filetype( $src );
							$mimetype = ( $filetype['ext'] == 'm3u8' ) ? 'application/x-mpegurl' : ( $filetype['ext'] == 'flv' ? 'video/flash' : 'video/mp4' );
							$sources .= '<source type="' . $mimetype . '" src="' . $src . '">';
							break;					
						case 'rtmp' :
							$sources .= '<source type="video/flash" src="' . $row['flash'] . '" data-rtmp="' . $src . '">';
							unset( $row['flash'] );
							break;
						case 'captions' :
							$sources .= '<track src="' . $src . '">';
							break;
						default :
							$sources .= '<source type="video/' . $type . '" src="' . $src . '">';
					};
				};
			};
			
			$video_element = '<div class="yendifplayer" data-vid="'.$id.'"><video>' . $sources . '</video></div>';
		};		
		
	?>
    <!DOCTYPE html>
    <html>
       	<head>
           	<link rel="stylesheet" href="<?php echo YENDIF_PLAYER_PLUGIN_URL; ?>/public/assets/libraries/yendifplayer.css" />
            <style type="text/css">
				body, iframe {
					margin:0 !important;
					padding:0 !important;
					background:transparent !important;
				}
            </style>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
            <script src="<?php echo YENDIF_PLAYER_PLUGIN_URL; ?>/public/assets/libraries/yendifplayer.js" type="text/javascript"></script>
            <script type="text/javascript">
				yendifplayer.config = <?php echo json_encode( $config ); ?>;
				
				$(document).ready(function(){
    				$(".yendifplayer").css( { height:$(window).height(), width:$(window).width() } );
				});
			</script>
        </head>
        <body>
        	<?php echo $video_element; ?>
        </body>
    </html>	
	<?php
		exit();
	}
	
	/**
	 * Download the requested Video/Audio.
	 *
	 * @since    2.3.0
	 */	
	public function download( $id ) {	
	
		// hide notices
		if( ini_get('error_reporting') )  {
			ini_set('error_reporting', E_ALL & ~ E_NOTICE);
		}
 
		//- turn off compression on the server
		if( function_exists('apache_setenv') ) {
			apache_setenv('no-gzip', 1);
		}
		
		if( ini_get('zlib.output_compression') )  {
			ini_set('zlib.output_compression', 'Off');
		}
		
		// get file path from database
		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_media';
		$sql = "SELECT mp4 FROM $table WHERE type != 'audio' AND published=1 AND id=" . $id;	
		$file_path = $wpdb->get_var( $sql );		
 		$file_name = basename($file_path);		

		// allow a file to be streamed instead of sent as an attachment
		$is_attachment = isset($_REQUEST['stream']) ? false : true;
 
		// make sure the file exists
		$relative_file_path = $_SERVER['DOCUMENT_ROOT'].wp_make_link_relative($file_path);	
		
		if( is_file($relative_file_path) ) {
			header("Content-type: octet/stream");
			header("Content-disposition: attachment; filename=".$file_name.";");
			header("Content-Length: ".filesize($relative_file_path));
			ob_clean();
			$handle = fopen($relative_file_path, 'rb');
			while( !feof($handle) ) { 
    			echo fread($handle, 8192);
    			flush();
			}
			fclose($handle);
			exit;
		} else {
			// file couldn't be opened
			//header("HTTP/1.0 500 Internal Server Error");
			//exit;
			/*echo '<script> alert("'.__('Sorry, this file could not be downloaded.').'"); window.history.go(-1); </script>';*/
			$this->download_anything( $file_path, $file_name, '', true );
		}
		
	}
	
	function remove_spaces( $url ) { 
	    
		$url = preg_replace('/\s+/', '-', trim($url));
		$url = str_replace("         ","-",$url);
	  	$url = str_replace("        ","-",$url);
	  	$url = str_replace("       ","-",$url);
	  	$url = str_replace("      ","-",$url);
	  	$url = str_replace("     ","-",$url);
	  	$url = str_replace("    ","-",$url);
	  	$url = str_replace("   ","-",$url);
	  	$url = str_replace("  ","-",$url);
	  	$url = str_replace(" ","-",$url);
	
     	return $url;   
		  
	}
	
	function remove_url_spaces( $url ) {
	
        $url = preg_replace('/\s+/', '%20', trim($url));  
        $url = str_replace("         ","%20",$url);
        $url = str_replace("        ","%20",$url);
        $url = str_replace("       ","%20",$url);
        $url = str_replace("      ","%20",$url);
        $url = str_replace("     ","%20",$url);
        $url = str_replace("    ","%20",$url);
	    $url = str_replace("   ","%20",$url);
	    $url = str_replace("  ","%20",$url);
	    $url = str_replace(" ","%20",$url);
		
        return $url;   
		  
	}	
	
	function download_anything( $file, $newfilename = '', $mimetype='', $isremotefile = false ) {
	      
        $formattedhpath = "";
        $filesize = "";

        if(empty($file)){
           die('Please enter file url to download...!');
           exit;
        }
     
        //Removing spaces and replacing with %20 ascii code
        $file = $this->remove_url_spaces($file);
		
        if( preg_match("#http://#", $file) || preg_match("#https://#", $file) ) {
        	$formattedhpath = "url";
        } else {
          	$formattedhpath = "filepath";
        }
        
        if( $formattedhpath == "url" ) {
        	$file_headers = @get_headers($file);
  
          	if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
          		die('File is not readable or not found...!');
           		exit;
          	}          
        } else if( $formattedhpath == "filepath" ) {		
        	if( @is_readable($file) ) {
               die('File is not readable or not found...!');
               exit;
          	}					  
        }        
        
       	//Fetching File Size Located in Remote Server
       	if( $isremotefile && $formattedhpath == "url" ) {         
       		$data = @get_headers($file, true);
          
          	if( !empty($data['Content-Length']) ){
         		$filesize = (int)$data["Content-Length"];
          	} else {
               	///If get_headers fails then try to fetch filesize with curl
            	$ch = @curl_init();

               	if( !@curl_setopt($ch, CURLOPT_URL, $file) ) {
                	@curl_close($ch);
                 	@exit;
               	}
               
               	@curl_setopt($ch, CURLOPT_NOBODY, true);
               	@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
               	@curl_setopt($ch, CURLOPT_HEADER, true);
               	@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
               	@curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
               	@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
               	@curl_exec($ch);
               
               	if( !@curl_errno($ch) ) {                    
                	$http_status = (int)@curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if($http_status >= 200  && $http_status <= 300)
                    $filesize = (int)@curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
               	}
               	@curl_close($ch);
        	}          
       	} else if( $isremotefile && $formattedhpath == "filepath" ) {         
	   		die('Error : Need complete URL of remote file...!');
           	exit;		   
       	} else {         
			if( $formattedhpath == "url" ) {		   
			   	$data = @get_headers($file, true);
				$filesize = (int)$data["Content-Length"];			   
		   	} else if( $formattedhpath == "filepath" ) {		   
		    	$filesize = (int)@filesize($file);
		   	}		   
       	}
       
       	if( empty($newfilename) ) {
          	$newfilename =  @basename($file);
       	} else {
          	//Replacing any spaces with (-) hypen
          	$newfilename = $this->remove_spaces($newfilename);
       	}
       
       	if( empty($mimetype) ) {
       		//Get the extension of the file
       		$path_parts = @pathinfo($file);
       		$myfileextension = $path_parts["extension"];

        	switch( $myfileextension ) {          
            	///Image Mime Types
            	case 'jpg':
            		$mimetype = "image/jpg";
            		break;
            	case 'jpeg':
            		$mimetype = "image/jpeg";
            		break;
            	case 'gif':
            		$mimetype = "image/gif";
            		break;
            	case 'png':
            		$mimetype = "image/png";
            		break;
            	case 'bm':
            		$mimetype = "image/bmp";
            		break;
            	case 'bmp':
            		$mimetype = "image/bmp";
            		break;
            	case 'art':
            		$mimetype = "image/x-jg";
            		break;
            	case 'dwg':
            		$mimetype = "image/x-dwg";
            		break;
            	case 'dxf':
            		$mimetype = "image/x-dwg";
            		break;
            	case 'flo':
            		$mimetype = "image/florian";
            		break;
            	case 'fpx':
            		$mimetype = "image/vnd.fpx";
            		break;
            	case 'g3':
            		$mimetype = "image/g3fax";
            		break;
            	case 'ief':
            		$mimetype = "image/ief";
            		break;
            	case 'jfif':
            		$mimetype = "image/pjpeg";
            		break;
            	case 'jfif-tbnl':
            		$mimetype = "image/jpeg";
            		break;
            	case 'jpe':
            		$mimetype = "image/pjpeg";
            		break;
            	case 'jps':
            		$mimetype = "image/x-jps";
            		break;
            	case 'jut':
            		$mimetype = "image/jutvision";
            		break;
            	case 'mcf':
            		$mimetype = "image/vasa";
            		break;
            	case 'nap':
            		$mimetype = "image/naplps";
            		break;
            	case 'naplps':
            		$mimetype = "image/naplps";
            		break;
            	case 'nif':
            		$mimetype = "image/x-niff";
            		break;
            	case 'niff':
            		$mimetype = "image/x-niff";
            		break;
            	case 'cod':
            		$mimetype = "image/cis-cod";
            		break;
            	case 'ief':
            		$mimetype = "image/ief";
            		break;
            	case 'svg':
            		$mimetype = "image/svg+xml";
            		break;
            	case 'tif':
            		$mimetype = "image/tiff";
            		break;
            	case 'tiff':
            		$mimetype = "image/tiff";
            		break;
            	case 'ras':
            		$mimetype = "image/x-cmu-raster";
            		break;
            	case 'cmx':
            		$mimetype = "image/x-cmx";
            		break;
            	case 'ico':
            		$mimetype = "image/x-icon";
            		break;
            	case 'pnm':
            		$mimetype = "image/x-portable-anymap";
            		break;
            	case 'pbm':
            		$mimetype = "image/x-portable-bitmap";
            		break;
            	case 'pgm':
            		$mimetype = "image/x-portable-graymap";
            		break;
            	case 'ppm':
            		$mimetype = "image/x-portable-pixmap";
            		break;
            	case 'rgb':
            		$mimetype = "image/x-rgb";
            		break;
            	case 'xbm':
            		$mimetype = "image/x-xbitmap";
            		break;
            	case 'xpm':
            		$mimetype = "image/x-xpixmap";
            		break;
            	case 'xwd':
            		$mimetype = "image/x-xwindowdump";
            		break;
            	case 'rgb':
            		$mimetype = "image/x-rgb";
            		break;
            	case 'xbm':
            		$mimetype = "image/x-xbitmap";
            		break;
            	case "wbmp":
            		$mimetype = "image/vnd.wap.wbmp";
            		break;
          
            	//Files MIME Types
            	case 'css':
            		$mimetype = "text/css";
            		break;
            	case 'htm':
            		$mimetype = "text/html";
            		break;
            	case 'html':
            		$mimetype = "text/html";
            		break;
            	case 'stm':
            		$mimetype = "text/html";
            		break;
            	case 'c':
            		$mimetype = "text/plain";
            		break;
            	case 'h':
            		$mimetype = "text/plain";
            		break;
            	case 'txt':
            		$mimetype = "text/plain";
            		break;
            	case 'rtx':
            		$mimetype = "text/richtext";
            		break;
            	case 'htc':
            		$mimetype = "text/x-component";
            		break;
            	case 'vcf':
            		$mimetype = "text/x-vcard";
            		break;
           
            	//Applications MIME Types
            	case 'doc':
            		$mimetype = "application/msword";
            		break;
            	case 'xls':
            		$mimetype = "application/vnd.ms-excel";
            		break;
            	case 'ppt':
            		$mimetype = "application/vnd.ms-powerpoint";
            		break;
            	case 'pps':
            		$mimetype = "application/vnd.ms-powerpoint";
            		break;
            	case 'pot':
            		$mimetype = "application/vnd.ms-powerpoint";
            	case "ogg":
            		$mimetype = "application/ogg";
            		break;
            	case "pls":
            		$mimetype = "application/pls+xml";
            		break;
            	case "asf":
            		$mimetype = "application/vnd.ms-asf";
            		break;
            	case "wmlc":
            		$mimetype = "application/vnd.wap.wmlc";
            		break;
            	case 'dot':
            		$mimetype = "application/msword";
            		break;
            	case 'class':
            		$mimetype = "application/octet-stream";
            		break;
            	case 'exe':
            		$mimetype = "application/octet-stream";
            		break;
            	case 'pdf':
            		$mimetype = "application/pdf";
            		break;
            	case 'rtf':
            		$mimetype = "application/rtf";
            		break;
            	case 'xla':
            		$mimetype = "application/vnd.ms-excel";
            		break;
            	case 'xlc':
            		$mimetype = "application/vnd.ms-excel";
            		break;
            	case 'xlm':
            		$mimetype = "application/vnd.ms-excel";
            		break;
            	case 'msg':
            		$mimetype = "application/vnd.ms-outlook";
            		break;
            	case 'mpp':
            		$mimetype = "application/vnd.ms-project";
            		break;
            	case 'cdf':
            		$mimetype = "application/x-cdf";
            		break;
            	case 'tgz':
            		$mimetype = "application/x-compressed";
            		break;
            	case 'dir':
            		$mimetype = "application/x-director";
            		break;
            	case 'dvi':
            		$mimetype = "application/x-dvi";
            		break;
            	case 'gz':
            		$mimetype = "application/x-gzip";
            		break;
            	case 'js':
            		$mimetype = "application/x-javascript";
            		break;
            	case 'mdb':
            		$mimetype = "application/x-msaccess";
            		break;
            	case 'dll':
            		$mimetype = "application/x-msdownload";
            		break;
            	case 'wri':
            		$mimetype = "application/x-mswrite";
            		break;
            	case 'cdf':
            		$mimetype = "application/x-netcdf";
            		break;
            	case 'swf':
            		$mimetype = "application/x-shockwave-flash";
            		break;
            	case 'tar':
            		$mimetype = "application/x-tar";
            		break;
            	case 'man':
            		$mimetype = "application/x-troff-man";
            		break;
            	case 'zip':
            		$mimetype = "application/zip";
            		break;
            	case 'xlsx':
            		$mimetype = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
            		break;
            	case 'pptx':
            		$mimetype = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
            		break;
            	case 'docx':
            		$mimetype = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
            		break;
            	case 'xltx':
            		$mimetype = "application/vnd.openxmlformats-officedocument.spreadsheetml.template";
            		break;
            	case 'potx':
            		$mimetype = "application/vnd.openxmlformats-officedocument.presentationml.template";
            		break;
            	case 'ppsx':
            		$mimetype = "application/vnd.openxmlformats-officedocument.presentationml.slideshow";
            		break;
            	case 'sldx':
            		$mimetype = "application/vnd.openxmlformats-officedocument.presentationml.slide";
            		break;
          	
            	//Audio and Video Files
            	case 'mp3':
            		$mimetype = "audio/mpeg";
            		break;
            	case 'wav':
            		$mimetype = "audio/x-wav";
            		break;
            	case 'au':
            		$mimetype = "audio/basic";
            		break;
            	case 'snd':
            		$mimetype = "audio/basic";
            		break;
            	case 'm3u':
            		$mimetype = "audio/x-mpegurl";
            		break;
            	case 'ra':
            		$mimetype = "audio/x-pn-realaudio";
            		break;
            	case 'mp2':
            		$mimetype = "video/mpeg";
            		break;
            	case 'mov':
            		$mimetype = "video/quicktime";
            		break;
            	case 'qt':
            		$mimetype = "video/quicktime";
            		break;				
            	case 'mp4':
					$mimetype = "video/mp4";
            		break;
				case 'm4v':
            		$mimetype = "video/mp4";
            		break;
            	case 'm4a':
            		$mimetype = "audio/mp4";
            		break;
            	case 'mp4a':
            		$mimetype = "audio/mp4";
            		break;
            	case 'm4p':
            		$mimetype = "audio/mp4";
            		break;
            	case 'm3a':
            		$mimetype = "audio/mpeg";
            		break;
            	case 'm2a':
            		$mimetype = "audio/mpeg";
            		break;
            	case 'mp2a':
            		$mimetype = "audio/mpeg";
            		break;
            	case 'mp2':
            		$mimetype = "audio/mpeg";
            		break;
            	case 'mpga':
            		$mimetype = "audio/mpeg";
            		break;
            	case '3gp':
            		$mimetype = "video/3gpp";
            		break;
            	case '3g2':
            		$mimetype = "video/3gpp2";
            		break;
            	case 'mp4v':
            		$mimetype = "video/mp4";
            		break;
            	case 'mpg4':
            		$mimetype = "video/mp4";
            		break;
            	case 'm2v':
            		$mimetype = "video/mpeg";
            		break;
            	case 'm1v':
           			$mimetype = "video/mpeg";
            		break;
            	case 'mpe':
            		$mimetype = "video/mpeg";
            		break;
            	case 'avi':
            		$mimetype = "video/x-msvideo";
            		break;
            	case 'midi':
            		$mimetype = "audio/midi";
            		break;
            	case 'mid':
            		$mimetype = "audio/mid";
            		break;
            	case 'amr':
            		$mimetype = "audio/amr";
            		break;
 
            	default:
            		$mimetype = "application/octet-stream";
        	}   
        
		}

        //off output buffering to decrease Server usage
        @ob_end_clean();
        
        if( ini_get('zlib.output_compression') ) {
        	ini_set('zlib.output_compression', 'Off');
        }
        
        header('Content-Description: File Transfer');
        header('Content-Type: '.$mimetype);
        header('Content-Disposition: attachment; filename='.$newfilename.'');
        header('Content-Transfer-Encoding: binary');
        header("Expires: Wed, 07 May 2013 09:09:09 GMT");
	    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	    header('Cache-Control: post-check=0, pre-check=0', false);
	    header('Cache-Control: no-store, no-cache, must-revalidate');
	    header('Pragma: no-cache');
        header('Content-Length: '.$filesize);
        
        //Will Download 1 MB in chunkwise
        $chunk = 1 * (1024 * 1024);
        $nfile = @fopen($file,"rb");
        while( !feof($nfile) ) {
        	print(@fread($nfile, $chunk));
            @ob_flush();
            @flush();
        }
        @fclose($filen);  
		
	}

}