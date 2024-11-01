<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */
 
$page = $_GET['page'];
$action = '?page=' . $page . '&action=save';

?>

<div class="wrap yendif-player <?php echo $page; ?>">
  <p class="yendif-player-notice">
    <span style="display:block; color:#a00">You're using the Yendif Player WordPress Plugin - FREE version. Upgrade to our <a href="http://yendifplayer.com/wordpress-plugin-download.html" target="_blank">PRO Version</a> and get access to all our pro features, receive updates & get lifetime support.</span>
    <span><strong><?php _e( 'Website', YENDIF_PLAYER_PLUGIN_SLUG ); ?> :</strong><a href="http://yendifplayer.com/" target="_blank">http://yendifplayer.com/</a></span>
    <span><strong><?php _e( 'Support Mail ID', YENDIF_PLAYER_PLUGIN_SLUG ); ?> :</strong><a href="mailto:admin@yendifplayer.com">admin@yendifplayer.com</a></span>
    <span><strong><?php _e( 'Ask in our Forum', YENDIF_PLAYER_PLUGIN_SLUG ); ?> :</strong><a href="http://yendifplayer.com/forum/yendif-player-wordpress-plugin.html" target="_blank"><?php _e( 'Click Here', YENDIF_PLAYER_PLUGIN_SLUG ); ?></a></span>
  </p>
  <form enctype="multipart/form-data" method="post" action="<?php echo $action; ?>" class="<?php echo $page; ?>-form" id="yendif-player-form">
    <input type="hidden" name="yendif-player-page" id="yendif-player-page" value="<?php echo $page; ?>" />
    <?php wp_nonce_field('yendif-player-nonce'); ?>
    <div class="yendif-player-left-content">
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
          <?php _e( 'Basic settings', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <table class="yendif-player-form-table">
          <tr valign="top">
            <th scope="row"><?php _e( 'Player size', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radio(
						'responsive',
						array( __( 'Responsive', YENDIF_PLAYER_PLUGIN_SLUG ), __( 'Fixed size', YENDIF_PLAYER_PLUGIN_SLUG ) ),
						array( 1, 0 ),
						$item->responsive ); ?>
              	<p class="yendif-player-description">
                	<?php _e( 'Enable or disable responsive scaling.', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	</p>
            </td>
          </tr>
          <tr valign="top" class="yendif-player-row-fixed">
            <th scope="row"><label for="width"><?php _e( 'Fixed width', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
            	<input type="text" name="width" id="width" class="required yendif-player-small yendif-player-center" value="<?php echo $item->width; ?>" />
              	<span class="yendif-player-description"><?php _e( 'pixels wide', YENDIF_PLAYER_PLUGIN_SLUG );?></span>
            </td>
          </tr>
          <tr valign="top" class="yendif-player-row-fixed">
            <th scope="row"><label for="height"><?php _e( 'Fixed height', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
            	<input type="text" name="height" id="height" class="required yendif-player-small yendif-player-center" value="<?php echo $item->height; ?>" />
              	<span class="yendif-player-description"><?php _e( 'pixels high', YENDIF_PLAYER_PLUGIN_SLUG );?></span>
            </td>
          </tr>
          <tr valign="top" class="yendif-player-row-responsive">
            <th scope="row"><label for="ratio"><?php _e( 'Ratio', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
            	<input type="text" name="ratio" id="ratio" class="required yendif-player-small yendif-player-center" value="<?php echo $item->ratio; ?>" />
              	<span class="yendif-player-description"><?php _e( 'The player will use the full width of its enclosing container/html element and scale its height according to the aspect ratio.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
              	<p class="yendif-player-description"><strong><?php _e( 'Examples', YENDIF_PLAYER_PLUGIN_SLUG ); ?>: </strong></p>
              	<ul class="yendif-player-list">
                	<li><strong>0.5625</strong> - <?php _e( 'Wide screen TV', YENDIF_PLAYER_PLUGIN_SLUG ); ?></li>
                	<li><strong>0.625</strong> - <?php _e( 'Monitor screens', YENDIF_PLAYER_PLUGIN_SLUG ); ?></li>
                	<li><strong>0.75</strong> - <?php _e( 'Classic TV', YENDIF_PLAYER_PLUGIN_SLUG ); ?></li>
                	<li><strong>0.67</strong> - <?php _e( 'Photo camera', YENDIF_PLAYER_PLUGIN_SLUG ); ?></li>
                	<li><strong>1</strong> - <?php _e( 'Square', YENDIF_PLAYER_PLUGIN_SLUG ); ?></li>
                	<li><strong>0.417</strong> - <?php _e( 'Cinemascope', YENDIF_PLAYER_PLUGIN_SLUG ); ?></li>
              	</ul>
          	</td>
          </tr>
        </table>
      </div>
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
          <?php _e( 'Layout settings', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <table class="yendif-player-form-table">
          <tr valign="top">
            <th scope="row"><?php _e( 'Theme', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radio(
						'theme',
						array( __( 'Black', YENDIF_PLAYER_PLUGIN_SLUG ), __( 'White', YENDIF_PLAYER_PLUGIN_SLUG ) ),
						array( 'black', 'white' ),
						$item->theme ); ?>
              	<p class="yendif-player-description"><?php _e( 'Player skin colors. Pick the best one for your website.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Controlbar', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'controlbar', $item->controlbar, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( 'Show or hide the player controls.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Play button', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'playbtn', $item->playbtn, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( 'Show or hide the big play button in the middle of the video player.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Playpause', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'playpause', $item->playpause, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( 'Show or hide the play/pause control from the controlbar.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Currenttime', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'currenttime', $item->currenttime, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( "Show or hide the current playback time's display.", YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Progress', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'progress', $item->progress, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( 'Show or hide the progress bar.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Duration', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool('duration', $item->duration, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( "Show or hide the media's total duration.", YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Volume button', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'volumebtn', $item->volumebtn, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( 'Show or hide the volume control.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Fullscreen', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'fullscreen', $item->fullscreen, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( 'Show or hide the fullscreen button. This is valid only for video.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Embed', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'embed', $item->embed, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( 'Show or hide the embed option. This is valid only for video.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
            </td>
          </tr>          
          <tr valign="top">
            <th scope="row"><?php _e( 'Socialshare', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td><p class="yendif-player-description" style="color:#FF0000"><?php _e( 'PRO version only.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Download', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td><p class="yendif-player-description" style="color:#FF0000"><?php _e( 'PRO version only.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p></td>
          </tr>
        </table>
      </div>
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
          <?php _e( 'Playback settings', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <table class="yendif-player-form-table">
          <tr valign="top">
            <th scope="row"><?php _e( 'Engine', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radio(
						'engine',
						array( __( 'HTML5', YENDIF_PLAYER_PLUGIN_SLUG ), __( 'Flash', YENDIF_PLAYER_PLUGIN_SLUG ) ),
						array( 'html5', 'flash' ),
						$item->engine ); ?>
              	<p class="yendif-player-description"><?php _e( 'Default playback engine.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="volume"><?php _e( 'Volume', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
            	<input type="text" name="volume" id="volume" class="required yendif-player-small yendif-player-center" value="<?php echo $item->volume; ?>" />
              	<span class="yendif-player-description"><?php _e( "The playback's default volume level [0 - 100].", YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Autoplay', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'autoplay', $item->autoplay, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e('Control whether to start the playback automatically or wait for the user input.', YENDIF_PLAYER_PLUGIN_SLUG); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Loop', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'loop', $item->loop, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( 'If enabled, the player will start playback again from the beginning once the video is completed.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Keyboard', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'keyboard', $item->keyboard, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( 'Enable or disable keyboard controls.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
          	</td>
          </tr>
        </table>
      </div>
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
          <?php _e( 'Playlist settings', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <table class="yendif-player-form-table">
          <tr valign="top">
            <th scope="row"><label for="playlist_width"><?php _e( 'Playlist width', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
              <input type="text" name="playlist_width" id="playlist_width" class="required yendif-player-small yendif-player-center" value="<?php echo $item->playlist_width; ?>"/>
              <span class="yendif-player-description"><?php _e( 'pixels wide', YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="playlist_height"><?php _e( 'Playlist height', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
           	<input type="text" name="playlist_height" id="playlist_height" class="required yendif-player-small yendif-player-center" value="<?php echo $item->playlist_height; ?>"/>
              <span class="yendif-player-description"><?php _e( 'pixels high', YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Playlist position', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radio(
						'playlist_position',
						array( __( 'Right', YENDIF_PLAYER_PLUGIN_SLUG ), __( 'Bottom', YENDIF_PLAYER_PLUGIN_SLUG ) ),
						array( 'right', 'bottom' ),
						$item->playlist_position ); ?> 
              	<ul class="yendif-player-list">
                	<li>- <?php _e( 'When player size is less than 400px, the playlist is automatically moved to the player bottom.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></li>
                	<li>- <?php _e( 'In cases of audio, the playlist is always set at the bottom.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></li>
              	</ul>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Autoplaylist', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::radioBool( 'autoplaylist', $item->autoplaylist, YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	<p class="yendif-player-description"><?php _e( 'If enabled, the player will start playing the next media automatically after the current playback completes.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
          	</td>
          </tr>
        </table>
      </div>
    </div>
    <div class="yendif-player-right-content">
       <div class="yendif-player-box">
        <div class="yendif-player-box-content">
          <div class="yendif-player-box-inner">
            <p class="yendif-player-description"><?php _e( 'Every player will have these settings as default, but you can override them for individual players using appropriate <a href="?page=yendif-player-help#configuring_player" target="_blank">shortcode</a> properties.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', YENDIF_PLAYER_PLUGIN_SLUG ) ;?>">
          </div>
        </div>
      </div>
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
          <?php _e( 'Gallery settings', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <table class="yendif-player-form-table">
          <tr valign="top">
            <th scope="row"><label for="thumb_width"><?php _e( 'Sort By', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
              <select id="sort_order" name="sort_order"> 
    			<option value="latest" <?php if ( 'latest' == $item->sort_order ) echo 'selected'; ?>><?php _e('Latest videos', YENDIF_PLAYER_PLUGIN_SLUG); ?></option>
    			<option value="date_added" <?php if ( 'date_added' == $item->sort_order ) echo 'selected'; ?>><?php _e(' Date added', YENDIF_PLAYER_PLUGIN_SLUG); ?></option>
    			<option value="a_z" <?php if ( 'a_z' == $item->sort_order ) echo 'selected'; ?>><?php _e('A-Z', YENDIF_PLAYER_PLUGIN_SLUG); ?></option>
    			<option value="z_a" <?php if ( 'z_a' == $item->sort_order ) echo 'selected'; ?>><?php _e('Z-A', YENDIF_PLAYER_PLUGIN_SLUG); ?></option>
    			<option value="random" <?php if ( 'random' == $item->sort_order ) echo 'selected'; ?>><?php _e('Random videos', YENDIF_PLAYER_PLUGIN_SLUG); ?></option>
  	  		  </select>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="thumb_width"><?php _e( 'Thumbnail width', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
              <input type="text" name="thumb_width" id="thumb_width" class="required yendif-player-small yendif-player-center" value="<?php echo $item->thumb_width; ?>"/>
              <span class="yendif-player-description"><?php _e( 'pixels wide', YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="thumb_height"><?php _e( 'Thumbnail height', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
           	<input type="text" name="thumb_height" id="thumb_height" class="required yendif-player-small yendif-player-center" value="<?php echo $item->thumb_height; ?>"/>
              <span class="yendif-player-description"><?php _e( 'pixels high', YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="no_of_rows"><?php _e( 'Rows', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
              <input type="text" name="no_of_rows" id="no_of_rows" class="required yendif-player-small yendif-player-center" value="<?php echo $item->no_of_rows; ?>"/>
              <span class="yendif-player-description"><?php _e( 'numbers', YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="no_of_cols"><?php _e( 'Columns', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
              <input type="text" name="no_of_cols" id="no_of_cols" class="required yendif-player-small yendif-player-center" value="<?php echo $item->no_of_cols; ?>"/>
              <span class="yendif-player-description"><?php _e( 'numbers', YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="no_of_cols"><?php _e( 'Title limit', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
              <input type="text" name="title_chars_limit" id="title_chars_limit" class="yendif-player-small yendif-player-center" value="<?php echo $item->title_chars_limit; ?>"/>
              <span class="yendif-player-description"><?php _e( "characters. Add (0) to show full title.", YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Show description', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td><?php echo Yendif_Player_Admin_Functions::radioBool( 'show_desc', $item->show_desc, YENDIF_PLAYER_PLUGIN_SLUG ); ?></td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="no_of_cols"><?php _e( 'Description limit', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
              <input type="text" name="desc_chars_limit" id="desc_chars_limit" class="yendif-player-small yendif-player-center" value="<?php echo $item->desc_chars_limit; ?>"/>
              <span class="yendif-player-description"><?php _e( "characters. Add (0) to show full description and (-1) to hide description.", YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
             </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Show views', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td><?php echo Yendif_Player_Admin_Functions::radioBool( 'show_views', $item->show_views, YENDIF_PLAYER_PLUGIN_SLUG ); ?></td>
          </tr>
        </table>
      </div>
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
          <?php _e( 'Google analytics', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <table class="yendif-player-form-table">
          <tr valign="top">
            <th scope="row"><?php _e( 'Analytics', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
            	<input type="text" name="analytics" id="analytics" class="yendif-player-wide" value="<?php echo $item->analytics; ?>" />
              	<p class="yendif-player-description"><?php _e( 'Your google analytics UA code.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
          	</td>
          </tr>
        </table>
      </div>
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
          <?php _e( 'License settings', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <table class="yendif-player-form-table">
          <tr valign="top">
            <th scope="row"><label for="license"><?php _e( 'License', YENDIF_PLAYER_PLUGIN_SLUG ); ?></label></th>
            <td>
            	<input type="text" name="license" id="license" class="yendif-player-wide" value="<?php echo $item->license; ?>" />
              	<p class="yendif-player-description"><?php _e( 'Your commercial <a href="http://yendifplayer.com/commercial-license.html" target="_blank">license key</a>.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
          	</td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Logo', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::uploader( 'logo', YENDIF_PLAYER_PLUGIN_SLUG, 'image', $item->logo ); ?>
                <ul class="yendif-player-list">
                	<li>- <?php _e( 'JPG, PNG or GIF image to be used as watermark. When the player detected a valid key, then it hide our "Powered by" label and show your image added here.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></li>
                	<li>- <?php _e( 'We recommend using 24 bit PNG images with transparency, since they blend nicely with the video.', YENDIF_PLAYER_PLUGIN_SLUG ); ?></li>
              	</ul>
          	</td>
          </tr>
        </table>
      </div>
    </div>
  </form>
</div>