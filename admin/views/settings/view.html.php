<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */

class Yendif_Player_Settings_View {

	/**
	 * Instance of the model object.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	private $model = null;
	
	/**
	 * Constructor of this class.
	 *
	 * @since     1.0.0
	 */
	public function __construct( $model ) {
		$this->model = $model;
	}
	
	/**
	 * Load the default layout
	 *
	 * @since     1.0.0
	 */
	public function default_layout() {
	
		$item = $this->model->item();
		include_once( 'tmpl/default.php' );

	}	

}