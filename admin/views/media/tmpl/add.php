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
  <div class="yendif-player-header">
    <h2 class="yendif-player-left">
      <?php _e( 'Add New Media', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </h2>
    <div class="yendif-player-right">
      <a href="?page=<?php echo $page; ?>" class="yendif-player-backlink">
        <?php _e( "←&nbsp;&nbsp;Return To Media Overview", YENDIF_PLAYER_PLUGIN_SLUG ); ?>
      </a>
    </div>
    <div class="yendif-player-clear"></div>
    <hr />
  </div>
  <form enctype="multipart/form-data" method="post" action="<?php echo $action; ?>" class="<?php echo $page; ?>-form" id="yendif-player-form">
    <input type="hidden" name="yendif-player-page" id="yendif-player-page" value="<?php echo $page; ?>" />
    <?php wp_nonce_field('yendif-player-nonce'); ?>
    <div class="yendif-player-left-content">
      <input type="text" name="title" class="required yendif-player-title" placeholder="<?php _e( 'Enter title here', YENDIF_PLAYER_PLUGIN_SLUG ); ?>" />      
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
			<?php echo Yendif_Player_Admin_Functions::radio(
						'type',
						array( __( 'Video', YENDIF_PLAYER_PLUGIN_SLUG ), __( 'Youtube', YENDIF_PLAYER_PLUGIN_SLUG ), __( 'RTMP', YENDIF_PLAYER_PLUGIN_SLUG ), __( 'Audio', YENDIF_PLAYER_PLUGIN_SLUG ) ),
						array( 'video', 'youtube', 'rtmp', 'audio' ),
						'video' ); ?>
        </div>
        <table class="yendif-player-form-table">
          <tr valign="top" id="yendif-player-row-youtube">
            <th scope="row"><?php _e( 'YouTube URL', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
            	<input type="text" name="youtube" id="youtube" class="required yendif-player-wide" />
              	<p class="yendif-player-description"><?php _e( 'Example : http://youtu.be/dQw4w9WgXcQ', YENDIF_PLAYER_PLUGIN_SLUG ); ?></p>
            </td>
          </tr>
          <tr valign="top" id="yendif-player-row-rtmp">
            <th scope="row"><?php _e( 'RTMP Server', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
            	<input type="text" name="rtmp" id="rtmp" class="required yendif-player-wide" />
              	<p class="yendif-player-description">
                	<?php _e( 'Example : rtmp://server_ip_address/application/', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	</p>
            </td>
          </tr>
          <tr valign="top" id="yendif-player-row-flash">
            <th scope="row"><?php _e( 'Stream Name', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
            	<input type="text" name="flash" id="flash" class="required yendif-player-wide" />
              	<p class="yendif-player-description">
                	<?php _e( 'Example : stream.mp4', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	</p>
            </td>
          </tr>
          <tr valign="top" id="yendif-player-row-fallback">
            <th scope="row"><?php _e( 'Mobile Fallback Video&nbsp;(optional)', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::uploader( 'fallback', YENDIF_PLAYER_PLUGIN_SLUG, 'video' ); ?>
              	<p class="yendif-player-description">
                	<?php _e( 'MP4 | M4V | FLV | M3U8 formats', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	</p>
              	<p class="yendif-player-description">
                	<?php _e( "It's always recommended using <strong>Mp4</strong> as it gives a complete cross browser support with the aid of Flash. This makes your video playable anywhere.", YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	</p>
          	</td>
          </tr>
          <tr valign="top" id="yendif-player-row-mp4">
            <th scope="row"><?php _e( 'MP4 | M4V | FLV', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
				<?php echo Yendif_Player_Admin_Functions::uploader( 'mp4', YENDIF_PLAYER_PLUGIN_SLUG, 'video', '', 'required ' ); ?>
              	<p class="yendif-player-description">
                	<?php _e( "It's always recommended using <strong>Mp4</strong> as it gives a complete cross browser support with the aid of Flash. This makes your video playable anywhere.", YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	</p>
          	</td>
          </tr>
          <tr valign="top" id="yendif-player-row-webm">
            <th scope="row"><?php _e( 'WEBM&nbsp;(optional)', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td><?php echo Yendif_Player_Admin_Functions::uploader( 'webm', YENDIF_PLAYER_PLUGIN_SLUG, 'video' ); ?></td>
          </tr>
          <tr valign="top" id="yendif-player-row-mp3">
            <th scope="row"><?php _e( 'MP3', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td><?php echo Yendif_Player_Admin_Functions::uploader( 'mp3', YENDIF_PLAYER_PLUGIN_SLUG, 'audio', '', 'required ' ); ?></td>
          </tr>
          <tr valign="top" id="yendif-player-row-wav">
            <th scope="row"><?php _e( 'WAV&nbsp;(optional)', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td><?php echo Yendif_Player_Admin_Functions::uploader( 'wav', YENDIF_PLAYER_PLUGIN_SLUG, 'audio' ); ?></td>
          </tr>
          <tr valign="top" id="yendif-player-row-ogg">
            <th scope="row"><?php _e( 'OGG&nbsp;(optional)', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td><?php echo Yendif_Player_Admin_Functions::uploader( 'ogg', YENDIF_PLAYER_PLUGIN_SLUG, 'ogg' ); ?></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Poster&nbsp;(optional)', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td><?php echo Yendif_Player_Admin_Functions::uploader( 'poster', YENDIF_PLAYER_PLUGIN_SLUG, 'image' ); ?></td>
          </tr>
          <tr valign="top" id="yendif-player-row-vtt">
            <th scope="row"><?php _e( 'Subtitle&nbsp;(optional)', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td><?php echo Yendif_Player_Admin_Functions::uploader( 'captions', YENDIF_PLAYER_PLUGIN_SLUG, 'text' ); ?></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Duration&nbsp;(optional)', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
            	<input type="text" name="duration" id="duration" class="yendif-player-small yendif-player-center" placeholder="00:00" />
              	<span class="yendif-player-description">
                	<?php _e( 'Duration of the media. Displayed only in playlists.', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	</span>
            </td>
          </tr>
          <tr valign="top" id="yendif-player-row-views">
            <th scope="row"><?php _e( 'Views count', YENDIF_PLAYER_PLUGIN_SLUG ); ?></th>
            <td>
            	<input type="text" name="views" id="views" class="yendif-player-small yendif-player-center" />
              	<span class="yendif-player-description">
                	<?php _e( 'numbers', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
              	</span>
            </td>
          </tr>
        </table>
      </div>
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
          <?php _e( 'Description', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <div class="yendif-player-box-content">
          <div class="yendif-player-box-inner">
            <textarea id="description" name="description"></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="yendif-player-right-content">
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
          <?php _e( 'Status', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <div class="yendif-player-box-content">
        	<div class="yendif-player-box-inner">
                <label class="yendif-player-featured yendif-player-block" for="yendif-player-featured">
            		<input type="checkbox" id="yendif-player-featured" name="featured" value="1">
                	<span><?php _e( 'Featured', YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
            	</label>
        		<label class="yendif-player-published yendif-player-block" for="yendif-player-published">
            		<input type="checkbox" id="yendif-player-published" name="published" value="1" checked="checked">
                	<span><?php _e( 'Published', YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
            	</label>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save New Media', YENDIF_PLAYER_PLUGIN_SLUG ) ;?>">
        	</div>
        </div>
      </div>
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
          <?php _e( 'Playlists', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <div class="yendif-player-box-content">
          <div class="yendif-player-box-inner"> <?php echo Yendif_Player_Admin_Functions::playlists(); ?> </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="post_id" id="post_id" value="0">
  </form>
</div>