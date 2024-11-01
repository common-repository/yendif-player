<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */
 
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( YENDIF_PLAYER_PLUGIN_DIR . 'admin/includes/table.php' );
}

class Yendif_Player_Media_View extends WP_List_Table {

	/**
	 * Instance of the model object.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	private $model = null;
	
	/**
	 * An associative array that hold all playlist names.
	 *
	 * @since    1.0.0
	 *
	 * @var      Array
	 */
	private $playlists = null;
	
	/**
	 * Constructor of this class.
	 *
	 * @since     1.0.0
	 */
	public function __construct( $model ) {
	
		$this->model = $model;
		
		global $status, $page;	      
        parent::__construct( array( 'singular' => 'medium', 'plural' => 'media', 'ajax' => false ) );
		
	}
	
	/**
	 * Load the default layout
	 *
	 * @since     1.0.0
	 */
	public function default_layout() {
		
		$this->playlists = $this->model->playlists();
		include_once( 'tmpl/default.php' );

	}
	
	/**
	 * Load the add layout
	 *
	 * @since     1.0.0
	 */
	public function add_layout() {
		include_once( 'tmpl/add.php' );
	}
	
	/**
	 * Load the edit layout
	 *
	 * @since     1.0.0
	 */
	public function edit_layout() {
	
		$item = $this->model->item();
		include_once( 'tmpl/edit.php' );
		
	}
	
	/**
	 * Prepare tabular data for display in default layout. Usually used to
     * query the database, sort and filter the data, and generally get it ready
	 * to be displayed.
	 *
	 * @since     1.0.0
	 */	 
	public function prepare_items() {
	
		// Decide how many records per page to show
		$per_page = $this->get_items_per_page( 'items_per_page', 5 );
		
		// Define our column headers
  		$columns = $this->get_columns();
  		$hidden = array();
  		$sortable = $this->get_sortable_columns();
		
  		$this->_column_headers = array($columns, $hidden, $sortable);
		
		// Query database and load all rows from "yendif_player_playlists" table
		$items = $this->model->items();
		
		// Used in pagination. Figure out what page the user is currently looking at.
  		$current_page = $this->get_pagenum();
		
		// Used in pagination. Check how many items are in our data array.
  		$total_items = count($items);
		
		// Trim data only to the current page.
  		$items = array_slice($items, (($current_page-1)*$per_page), $per_page);

		// Register our pagination options & calculations.
  		$this->set_pagination_args( array(
    		'total_items' => $total_items,
    		'per_page'    => $per_page
  		) );
  
  		// Add our "sorted" data to the items property
  		$this->items = $items;
		
	}
	
	/**
	 * Dictates the table's columns and titles.
	 *
	 * @since     1.0.0
	 *
	 * @return      Array		An associative array containing column information
	 */	  
	public function get_columns() {
	
  		$columns = array(
			'cb' => '<input type="checkbox" />',
    		'title' => __( 'Media Title', YENDIF_PLAYER_PLUGIN_SLUG ),	
			'id' => __( 'Media ID', YENDIF_PLAYER_PLUGIN_SLUG ),
			'playlists' => __( 'Playlists', YENDIF_PLAYER_PLUGIN_SLUG ),
			'featured' => __( 'Featured', YENDIF_PLAYER_PLUGIN_SLUG ),
			'shortcode' => __( 'Shortcode', YENDIF_PLAYER_PLUGIN_SLUG ),	
			'action' => __( 'Actions', YENDIF_PLAYER_PLUGIN_SLUG )		
  		);
		
  		return $columns;
		
	}
	
	/**
	 * Register sortable columns
	 *
	 * @since     1.0.0
	 *
	 * @return      Array		An associative array containing all the columns that should be sortable
	 */	
	public function get_sortable_columns() {
	
  		$sortable_columns = array(			
    		'title' => array('title', false),
			'id' => array('id', false)
  		);
		
  		return $sortable_columns;
		
	}

	/**
	 * Called when the parent class can't find a method specifically
     * build for a given column.
	 *
	 * @since     1.0.0
	 *
	 * @return      string		Text or HTML to be placed inside the column <td>
	 */	
	public function column_default( $item, $column_name ) {
	
  		switch( $column_name ) { 
			case 'id':
			case 'title':
			case 'featured':
      			return $item[ $column_name ];
			case 'playlists' :
				$lists = trim( $item[$column_name] );
				if( ! empty( $lists ) ) {
					$lists = explode( ' ', $lists );
					$count = count( $lists );
					$data = '<ul class="yendif-player-list">';
					for ( $i = 0; $i < $count; $i++ ) {
						$data .= '<li>' . $this->playlists[$lists[$i]] . '</li>';
					};
					$data .= '</ul>';
				};
				return $data;
			case 'shortcode' :
				$attr = $item['type'] == 'audio' ? 'audio=' . $item['id'] : 'video=' . $item['id'];
				return '<div style="margin-top:4px;">[yendifplayer '.$attr.']</div>';
			case 'action':
				$actions = array();
				
				$actions['edit'] = sprintf('<a href="?page=%s&action=%s&id=%s">'.__( 'Edit', YENDIF_PLAYER_PLUGIN_SLUG ).'</a>',$_GET['page'],'edit',$item['id']);
				if($item['published']) {
					$actions['unpublish'] = sprintf('<a href="?page=%s&action=%s&id=%s">'.__('Unpublish', YENDIF_PLAYER_PLUGIN_SLUG).'</a>',$_GET['page'],'unpublish',$item['id']);
				} else {
					$actions['publish'] = sprintf( '<a href="?page=%s&action=%s&id=%s">'.__('Publish', YENDIF_PLAYER_PLUGIN_SLUG).'</a>',$_GET['page'],'publish',$item['id'] );
				}
				$actions['delete'] = sprintf( '<a href="?page=%s&action=%s&id=%s">'.__('Delete', YENDIF_PLAYER_PLUGIN_SLUG).'</a>',$_GET['page'],'delete',$item['id'] );
		
  				return $this->row_actions($actions, true);
    		default:
				// Show the whole array for troubleshooting purposes
      			return print_r( $item, true );
  		}
		
	}	
	
	/**
	 * Displaying checkboxes for bulk actions
	 *
	 * @since     1.0.0
	 *
	 * @return      string		Text or HTML to be placed inside the column <td>
	 */	
	public function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="id[]" value="%s" />', $item['id'] );    
    }
	
	/**
	 * Define bulk actions
	 *
	 * @since     1.0.0
	 *
	 * @return      string		An associative array containing all the bulk actions
	 */	
	public function get_bulk_actions() {
	
  		$actions = array(
    		'delete'    => __( 'Delete', YENDIF_PLAYER_PLUGIN_SLUG ),			
			'publish'   => __( 'Publish', YENDIF_PLAYER_PLUGIN_SLUG ),
			'unpublish' => __( 'Unpublish', YENDIF_PLAYER_PLUGIN_SLUG )
  		);
		
  		return $actions;
		
	}
	
	/**
	 * We handle this action in model. So, it's better to override parent class
	 * used for the same.
	 *
	 * @since     1.0.0
	 */
	function process_bulk_action() {
		
    }

}