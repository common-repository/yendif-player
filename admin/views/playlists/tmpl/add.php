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
	  <?php _e( 'Add New Playlist', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </h2>
    <div class="yendif-player-right">
      <a href="?page=<?php echo $page; ?>" class="yendif-player-backlink">
       	<?php _e( "←&nbsp;&nbsp;Return To Playlists Overview", YENDIF_PLAYER_PLUGIN_SLUG ); ?>
      </a>
    </div>
    <div class="yendif-player-clear"></div>
    <hr />
  </div>
  <form method="post" action="<?php echo $action; ?>" class="<?php echo $page; ?>-form" id="yendif-player-form">    
    <input type="hidden" name="yendif-player-page" id="yendif-player-page" value="<?php echo $page; ?>" />
    <?php wp_nonce_field('yendif-player-nonce'); ?>
    <div class="yendif-player-left-content">
      <input type="text" name="name" class="required yendif-player-title" placeholder="<?php _e( 'Enter name here', YENDIF_PLAYER_PLUGIN_SLUG ); ?>" />
      <div class="yendif-player-box">
        <div class="yendif-player-box-header">
	      <?php _e( 'Image&nbsp;(optional)', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
        </div>
        <div class="yendif-player-box-content">
          <div class="yendif-player-box-inner">
            <?php echo Yendif_Player_Admin_Functions::uploader( 'image', YENDIF_PLAYER_PLUGIN_SLUG, 'image' ); ?>
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
            <label class="yendif-player-published" for="yendif-player-published">
              <input type="checkbox" id="yendif-player-published" name="published" value="1" checked="checked">
              <span><?php _e( 'Published', YENDIF_PLAYER_PLUGIN_SLUG ); ?></span>
            </label>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save New Playlist', YENDIF_PLAYER_PLUGIN_SLUG ) ;?>">
          </div>
        </div>
      </div>
    </div> 
    <div class="yendif-player-clear"></div>
     <input type="hidden" name="post_id" id="post_id" value="0">
  </form>
</div>