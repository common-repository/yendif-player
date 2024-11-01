<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */
 
class Yendif_Player_Admin_Controller {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Constructor of this class.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		
		// Add screen options
		add_filter( 'set-screen-option', array( $this, 'set_screen_option' ), 10, 3 );
		
		// Add custom mime types
		add_filter( 'upload_mimes', array( $this, 'custom_upload_mimes' ) );

		// Add an action link pointing to the options page.		
		$plugin_basename = plugin_basename( YENDIF_PLAYER_PLUGIN_DIR . YENDIF_PLAYER_PLUGIN_SLUG . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
		
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
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}
		
		wp_enqueue_style( YENDIF_PLAYER_PLUGIN_SLUG .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), YENDIF_PLAYER_VERSION_NUM );

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script( 'jquery' );
    	wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( YENDIF_PLAYER_PLUGIN_SLUG . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), YENDIF_PLAYER_VERSION_NUM );
		wp_localize_script( YENDIF_PLAYER_PLUGIN_SLUG . '-admin-script',
							'yendifplayer_locale',
							array( 'title' => __( 'Add New Media', YENDIF_PLAYER_PLUGIN_SLUG ),
								   'button' => __( 'Insert Selected Media', YENDIF_PLAYER_PLUGIN_SLUG ),
								   'required_alert' => __( 'Sorry, unable to submit the form. Some of the fields are empty.', YENDIF_PLAYER_PLUGIN_SLUG ) )	);

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
			
		// Add a top-level admin menu
    	add_menu_page(
			__( 'Settings', YENDIF_PLAYER_PLUGIN_SLUG ),
			__( 'Yendif Player', YENDIF_PLAYER_PLUGIN_SLUG ),
			'manage_options',
			'yendif-player-settings',
			array( $this, 'display_plugin_admin_page' ),
			plugins_url( 'assets/images/icon.png', __FILE__ )
		);
		
		// Add submenu page with same slug as parent to ensure no duplicates
		add_submenu_page(
			'yendif-player-settings',
			__( 'Player settings', YENDIF_PLAYER_PLUGIN_SLUG ),
			__( 'Player settings', YENDIF_PLAYER_PLUGIN_SLUG ),
			'manage_options',
			'yendif-player-settings',
			array( $this, 'display_plugin_admin_page' )
		);
		
		// Add submenu page for "Media"
		$menu_media = add_submenu_page(
			'yendif-player-settings',
			__( 'Media management', YENDIF_PLAYER_PLUGIN_SLUG ),
			__( 'Media management', YENDIF_PLAYER_PLUGIN_SLUG ),
			'manage_options',
			'yendif-player-media',
			array( $this, 'display_plugin_admin_page' )
		);		
		
		// Add submenu page for "Playlists"
		$menu_playlists = add_submenu_page(
			'yendif-player-settings',
			__( 'Playlist management', YENDIF_PLAYER_PLUGIN_SLUG ),
			__( 'Playlist management', YENDIF_PLAYER_PLUGIN_SLUG ),
			'manage_options',
			'yendif-player-playlists',
			array( $this, 'display_plugin_admin_page' )
		);
		
		// Add submenu page for "Help"
		$this->plugin_screen_hook_suffix = add_submenu_page(
			'yendif-player-settings',
			__( 'Help', YENDIF_PLAYER_PLUGIN_SLUG ),
			__( 'Help', YENDIF_PLAYER_PLUGIN_SLUG ),
			'manage_options',
			'yendif-player-help',
			array( $this, 'display_plugin_admin_page' )
		);
		
		$action = $this->current_action();
		if( ! in_array( $action, array( 'add', 'edit' ) ) ) {
			add_action( "load-$menu_media", array( $this, 'add_options' ) );
			add_action( "load-$menu_playlists", array( $this, 'add_options' ) );
		};
		
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {		
		
		// Get query vars
		$current = str_replace( 'yendif-player-', '', $_GET["page"] );
		$action = $this->current_action();
		
		if( ! in_array( $action, array( 'add', 'edit' ) ) ) {
			// Initialize tab
			$pages  = array(
						'settings' => __( 'Player settings', YENDIF_PLAYER_PLUGIN_SLUG ),
						'media' => __( 'Media management', YENDIF_PLAYER_PLUGIN_SLUG ),
						'playlists' => __( 'Playlist management', YENDIF_PLAYER_PLUGIN_SLUG ),
						'help' => __( 'Help', YENDIF_PLAYER_PLUGIN_SLUG ) );		
			$links = array();
		
			foreach ( $pages as $page => $title ) {
				if ( $page == $current) {
					$links[] = '<a class="nav-tab nav-tab-active" href="' . admin_url( 'admin.php?page=yendif-player-' . $page ) . '">' . __( $title, YENDIF_PLAYER_PLUGIN_SLUG ) . '</a>';
				} else {
					$links[] = '<a class="nav-tab" href="' . admin_url( 'admin.php?page=yendif-player-' . $page ) . '">' . __( $title, YENDIF_PLAYER_PLUGIN_SLUG ) . '</a>';
				}
			}
	
			echo "<h2 class='nav-tab-wrapper'>";
			foreach ( $links as $link ) {
				echo $link;
			}
			echo "</h2>";
		};
		
		// Set a model for the current page			
		$this->set_model( $current, $action );
	}
	
	/**
	 * Initialize the model
	 *
	 * @since    1.0.0
	 */
	private function set_model( $current, $action ) {
	
		include_once( YENDIF_PLAYER_PLUGIN_DIR . 'admin/models/' . $current . '.php' );		
		$modelClass  = 'Yendif_Player_' . ucfirst($current) . '_Model';
		$model = new $modelClass();
		switch($action) {
			case 'save' : $model->save();  break;
			case 'delete' : $model->delete(); break;
			case 'publish' : $model->publish( 1 ); break;
			case 'unpublish' : $model->publish( 0 ); break;
			default : $this->set_view( $model, $current, $action );
		}

	}
	
	/**
	 * Initialize the view
	 *
	 * @since    1.0.0
	 */
	private function set_view( $model, $current, $action ) {
	
		include_once( YENDIF_PLAYER_PLUGIN_DIR . 'admin/views/' . $current . '/view.html.php' );		
		$viewClass  = 'Yendif_Player_' . ucfirst($current) . '_View';
		$view = new $viewClass( $model );
		switch($action) {
			case 'add'  : $view->add_layout();  break;
			case 'edit' : $view->edit_layout(); break;
			default : $view->default_layout();
		}

	}

	/**
	 * Add screen options to the grid page.
	 *
	 * @since    1.0.0
	 */
	public function add_options() {
	
		$option = 'per_page';
		
  		$args = array(
        	'label' => __( 'Items', YENDIF_PLAYER_PLUGIN_SLUG ),
         	'default' => 10,
         	'option' => 'items_per_page'
        );
		 
  		add_screen_option( $option, $args );

	}
	
	/**
	 * Set our screen option values in the options table.
	 *
	 * @since    1.0.0
	 */
	public function set_screen_option( $status, $option, $value ) {	
		return $value;
	}
	
	/**
	 * Add custom mime types. So, these file types are allowed to
	 * upload using media uploader.
	 *
	 * @since    1.0.0
	 */
	public function custom_upload_mimes( $mimes = array() ) {	
		
		$mimes['vtt'] = 'text/vtt';
    	return $mimes;
	
	}
	
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
	
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=yendif-player-settings') . '">' . __( 'Settings', YENDIF_PLAYER_PLUGIN_SLUG ) . '</a>'
			),
			$links
		);

	}
	
	/**
	 * Get the current action selected from the bulk actions dropdown.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @return string|bool The action name or False if no action was selected
	 */
	public function current_action() {
	
		if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] )
			return $_REQUEST['action'];

		if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] )
			return $_REQUEST['action2'];

		return false;
		
	}

}