<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */
 
$page = $_GET['page'];
$action = '?page=' . $page . '&action=add';
$hidden = '<input type="hidden" name="page" value="' . $page . '" />';

?>

<div class="wrap yendif-player <?php echo $page; ?>">
  <p class="yendif-player-notice">
    <span style="display:block; color:#a00">You're using the Yendif Player WordPress Plugin - FREE version. Upgrade to our <a href="http://yendifplayer.com/wordpress-plugin-download.html" target="_blank">PRO Version</a> and get access to all our pro features, receive updates & get lifetime support.</span>
    <span><strong><?php _e( 'Note', YENDIF_PLAYER_PLUGIN_SLUG ); ?> : </strong><?php _e( 'Geting 404 error when clicking on videos from playlist? Kindly follow the solution', YENDIF_PLAYER_PLUGIN_SLUG ); ?><a href="http://yendifplayer.com/forum/general-discussions-wordpress/342-problem-with-showing-playlist.html#777" target="_blank"><?php _e( 'here', YENDIF_PLAYER_PLUGIN_SLUG ); ?></a></span>
  </p>
  <?php $this->prepare_items(); ?>
  <div class="yendif-player-left">
  	<a href="<?php echo $action; ?>" class="button-primary"><?php _e( 'Add New Media', YENDIF_PLAYER_PLUGIN_SLUG ); ?></a>
  </div>
  <form method="post" class="<?php echo $page; ?>-form-search" id="<?php echo $page; ?>-form-search">
    <?php echo $hidden; ?>
    <?php $this->search_box( __( 'Search by Title', YENDIF_PLAYER_PLUGIN_SLUG ), 'yendif_player_search' ); ?>
  </form>
  <div class="yendif-player-clear"></div>
  <form method="get" class="<?php echo $page; ?>-form-filter" id="<?php echo $page; ?>-form-filter">
    <?php echo $hidden; ?>
    <?php $this->display();	?>
  </form>
</div>