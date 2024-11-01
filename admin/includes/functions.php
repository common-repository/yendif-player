<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */
 
class Yendif_Player_Admin_Functions {

	/**
	 * Constructor of this class.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		
	}

	/**
	 * An utility function that create a radio button group.
	 *
	 * @since     1.0.0
	 *
	 * @return    string    Text or HTML that create a radio button group
	 */
	public static function radio( $name, $buttons, $values, $value ) {

		$html = '';
		$count = count( $buttons );
		for ( $i = 0; $i < $count; $i++ ) {
			$_value = $values[$i];
			$checked = ( $_value == $value ) ? 'checked' : '';
			
			$html .= '<label class="yendif-player-radio" for="' . ( $name . '-' . $_value ). '">';	
			$html .= '<input
						type="radio"
						name="' . $name . '"
						class="yendif-player-' . $name . '"
						id="' . ( $name . '-' . $_value ) . '"
						value="' . $_value . '" ' . $checked . ' />';
			$html .= '<span>' . $buttons[$i] . '</span></label>';
		}

		return $html;
		
	}
	
	/**
	 * An utility function that create a boolean button group.
	 *
	 * @since     1.0.0
	 *
	 * @return    string    Text or HTML that create a radio button group
	 */
	public static function radioBool( $name, $value, $plugin_slug = 'yendif-player' ) {

		$buttons = array( __( 'Yes', $plugin_slug ), __( 'No', $plugin_slug ) );
		$values = array( 1, 0 );
		$html = '';
		$count = count( $buttons );
		for ( $i = 0; $i < 2; $i++ ) {
			$_value = $values[$i];
			$checked = ( $_value == $value ) ? 'checked' : '';
			
			$html .= '<label class="yendif-player-radio" for="' . ( $name . '-' . $_value ). '">';	
			$html .= '<input
						type="radio"
						name="' . $name . '"
						class="yendif-player-' . $name . '"
						id="' . ( $name . '-' . $_value ) . '"
						value="' . $_value . '" ' . $checked . ' />';
			$html .= '<span>' . $buttons[$i] . '</span></label>';	
		}

		return $html;
		
	}
	
	/**
	 * An utility function that create a media uploader.
	 *
	 * @since     1.0.0
	 *
	 * @return    string    Text or HTML that create a file uploader
	 */
	public static function uploader( $name, $plugin_slug = 'yendif-player', $library = 'image', $value = '', $required = '' ) {
	
		$_name = 'type-' . $name;
		$html  = '<div class="yendif-player-media-uploader">';
		$html .= '<label class="yendif-player-radio" for="type-' . $name . '-1">';
		$html .= '<input
					type="radio"
					name="type-' . $name . '"
					class="yendif-player-type"
					id="type-' . $name . '-1"
					value="1" />';
		$html .= '<span>' . __( 'Direct URL', $plugin_slug ) . '</span></label>';
		$html .= '<label class="yendif-player-radio" for="type-' . $name . '-0">';
		$html .= '<input type="radio"
					name="type-' . $name . '"
					class="yendif-player-type"
					id="type-' . $name . '-0"
					value="0" checked />';
		$html .= '<span>' . __( 'Use Media Uploader', $plugin_slug ) . '</span></label>';
		$html .= '<br />';
		$html .= '<input type="text"
					name="' . $name . '"
					class="' . $required . 'yendif-player-wide"
					id="' . $name . '"
					value="' . $value . '" />';		
  		$html .= '<input
					type="button"
        			class="button yendif-player-upload-button"
                    name="upload_' . $name . '_button"
                    id="upload_' . $name . '_button"
                    data-yendif-player-attachment="' . $name . '"
					data-yendif-player-frame-library="' . $library . '"
                    value="' . __( 'Upload Media', $plugin_slug ) . '" />';
		$html .= '</div>';

		return $html;
		
	}
	
	/**
	 * Create checkboxes for playlists.
	 *
	 * @since     1.0.0
	 *
	 * @return    string    Text or HTML that create playlists multi checkbox
	 */
	public static function playlists( $values = '' ) {

		global $wpdb;
		
		$table = $wpdb->prefix . 'yendif_player_playlists';
		$sql = "SELECT * FROM $table WHERE published = 1";
		$items = $wpdb->get_results( $sql );

		$_values = explode( ' ', trim( $values ) );
		$html = '';
		foreach ( $items as $item ) {
			$checked = in_array( $item->id, $_values ) ? ' checked' : '';
			
			$html .= '<label class="yendif-player-playlist-label" for="playlist_' . $item->name . '">';
			$html .= '<input
						type="checkbox"
						id="playlist_' . $item->name . '"
						name="playlists[]"
						value="' . $item->id . '" ' . $checked . '/>';			
			$html .= '<span>' . $item->name . '</span></label>';
		}
        
		return $html;
		
	}
	
	/**
	 * Clean slashes in string.
	 *
	 * @since     1.2.0
	 *
	 * @return    string    Cleaned string output
	 */
	public static function no_magic_quotes( $text = '' ) {

		$data = explode( "\\", $text );
        $cleaned = implode( "", $data );
		
        return $cleaned;
		
	}

}