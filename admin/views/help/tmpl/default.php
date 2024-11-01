<?php
/**
 * @package   Yendif Player
 * @author    Yendif Technologies Pvt Ltd. (email : admin@yendifplayer.com)
 * @license   GPL-2.0+
 * @link      http://yendifplayer.com/
 * @copyright 2014 Yendif Technologies Pvt Ltd.
 */
 
$page = $_GET['page'];

?>

<div class="wrap yendif-player <?php echo $page; ?>">
	
    <p class="yendif-player-notice">
    	<span style="display:block; color:#a00">You're using the Yendif Player WordPress Plugin - FREE version. Upgrade to our <a href="http://yendifplayer.com/wordpress-plugin-download.html" target="_blank">PRO Version</a> and get access to all our pro features, receive updates & get lifetime support.</span>
   </p>
  
	<div class="yendif-player-description">
		<?php _e( "Like other wordpress plugins, Yendif Player also get intiated using shortcodes(<strong>[yendifplayer ...], [yendifgallery ...]</strong>).", YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </div>
    <div class="yendif-player-description">
		<strong>[yendifplayer] : </strong><?php _e( "used to add video or audio player in post or pages.", YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </div>
    <div class="yendif-player-description">
		<strong>[yendifgallery] : </strong><?php _e( "used to add video or playlist gallery in post or pages.", YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </div>
    <div class="yendif-player-description">
		<?php _e( "Yendif Player offers a vast amount of shortcode properties which keep the plugin unique from others. The following section lists all the available shortcode properties. Most of the properties are self explanatory. If you find any difficulties, we suggest to post your queries in our website's <a href='http://yendifplayer.com/forum/yendif-player-wordpress-plugin.html'>forum</a> section or directly mail us to <a href='mailto:admin@yendifplayer.com'>admin@yendifplayer.com</a>.", YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </div>
	<h3><?php _e( 'Playing video', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h3>
    <h4><?php _e( 'Playing video through ID', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifplayer video=<span class="maroon">X</span>]</div>
    <div class="yendif-player-description">[yendifplayer video=<span class="maroon">X,Y,Z</span>]</div>
    <div class="yendif-player-note">
		<?php _e( '<strong>Note : </strong>X,Y,Z from the above examples refer to the <a href="?page=yendif-player-media">MEDIA ID</a>.', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </div>
    <div class="yendif-player-spacer"></div>
    <h4><?php _e( 'Playing last added video', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifplayer video=<span class="maroon">latest</span>]</div>
    <div class="yendif-player-spacer"></div>
    <h4><?php _e( 'Playing a video in random each time the page loads', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifplayer video=<span class="maroon">random</span>]</div>
    <div class="yendif-player-spacer"></div>
    <h4><?php _e( 'Playing video through DIRECT URL', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifplayer type=<span class="maroon">video</span></div>
    <div class="yendif-player-description indent">mp4=<span class="maroon">http://mysite.com/video.mp4</span></div>
    <div class="yendif-player-description indent">webm=<span class="maroon">http://mysite.com/video.webm</span></div>
    <div class="yendif-player-description indent">ogg=<span class="maroon">http://mysite.com/video.ogv</span></div>
    <div class="yendif-player-description indent">poster=<span class="maroon">http://mysite.com/poster.jpg</span></div>
    <div class="yendif-player-description indent">captions=<span class="maroon">http://mysite.com/subtitle.vtt</span>]</div>
    <div class="yendif-player-spacer"></div>
    <h4><?php _e( 'Playing YouTube video through DIRECT URL', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifplayer type=<span class="maroon">video</span></div>
    <div class="yendif-player-description indent">youtube=<span class="maroon">http://www.youtube.com/watch?v=DEkAz6sgnZQ</span>]</div>    
    <div class="yendif-player-spacer"></div>
    <h4><?php _e( 'Playing RTMP stream through DIRECT URL', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifplayer type=<span class="maroon">video</span></div>
    <div class="yendif-player-description indent">rtmp=<span class="maroon">rtmp://server_ip_address/application</span></div>
    <div class="yendif-player-description indent">flash=<span class="maroon">stream.mp4</span></div>
    <div class="yendif-player-description indent">mp4=<span class="maroon">http://mysite.com/video.mp4</span></div>
    <div class="yendif-player-description indent">poster=<span class="maroon">http://mysite.com/poster.jpg</span></div>
    <div class="yendif-player-description indent">captions=<span class="maroon">http://mysite.com/subtitle.vtt</span>]</div>
    <div class="yendif-player-spacer"></div>
    <h3><?php _e( 'Playing audio', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h3>
     <h4><?php _e( 'Playing audio through ID', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifplayer audio=<span class="maroon">X</span>]</div>
    <div class="yendif-player-description">[yendifplayer audio=<span class="maroon">X,Y,Z</span>]</div>
    <div class="yendif-player-note">
		<?php _e( '<strong>Note : </strong>X,Y,Z from the above examples refer to the <a href="?page=yendif-player-media">MEDIA ID</a>.', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </div>
    <div class="yendif-player-spacer"></div>
     <h4><?php _e( 'Playing audio through DIRECT URL', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifplayer type=<span class="maroon">audio</span></div>
    <div class="yendif-player-description indent">mp3=<span class="maroon">http://mysite.com/audio.mp3</span></div>
    <div class="yendif-player-description indent">wav=<span class="maroon">http://mysite.com/audio.wav</span></div>
    <div class="yendif-player-description indent">ogg=<span class="maroon">http://mysite.com/audio.ogg</span>]</div>
    <div class="yendif-player-spacer"></div>
    <h3><?php _e( 'Configuring playlist', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h3>
    <div class="yendif-player-description">[yendifplayer type=<span class="maroon">video</span></div>
    <div class="yendif-player-description indent">playlist=<span class="maroon">X</span>]</div>
    <div class="yendif-player-description">[yendifplayer type=<span class="maroon">audio</span></div>
    <div class="yendif-player-description indent">playlist=<span class="maroon">X,Y,Z</span></div>
    <div class="yendif-player-description indent">limit=<span class="maroon">10</span>]</div>
    <div class="yendif-player-note">
		<?php _e( '<strong>Note : </strong>X,Y,Z from the above examples refer to the <a href="?page=yendif-player-playlists">PLAYLIST ID</a>.', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </div>
    <h3><?php _e( 'Control Sorting Order', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h3>
    <div class="yendif-player-description">[yendifplayer type=<span class="maroon">video</span></div>
    <div class="yendif-player-description indent">playlist=<span class="maroon">X,Y,Z</span></div>
    <div class="yendif-player-description indent">sort=<span class="maroon">latest <i>(or)</i> data_added <i>(or)</i> a_z <i>(or)</i> z_a <i>(or)</i> random</span></div>
    <div class="yendif-player-description indent">limit=<span class="maroon">10</span>]</div>
    <div  id="configuring_player" name="configuring_player" class="yendif-player-spacer"></div>
    <h3><?php _e( 'Configuring player', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h3>
    <div class="yendif-player-description">[yendifplayer engine=<span class="maroon">html5 <i>(or)</i> flash</span></div>
    <div class="yendif-player-description indent">width=<span class="maroon">640</span></div>
    <div class="yendif-player-description indent">height=<span class="maroon">360</span></div>
    <div class="yendif-player-description indent">responsive=<span class="maroon">0 <i>(or)</i> 1</span></div>
    <div class="yendif-player-description indent">ratio=<span class="maroon">0.5625</span></div>
    <div class="yendif-player-description indent">theme=<span class="maroon">black <i>(or)</i> white</span></div>
    <div class="yendif-player-description indent">autoplay=<span class="maroon">0 <i>(or)</i> 1</span></div>
    <div class="yendif-player-description indent">loop=<span class="maroon">0 <i>(or)</i> 1</span></div>
    <div class="yendif-player-description indent">volume=<span class="maroon">0 to 100</span></div>
    <div class="yendif-player-description indent">playbtn=<span class="maroon">0 <i>(or)</i> 1</span></div>
    <div class="yendif-player-description indent">controlbar=<span class="maroon">0 <i>(or)</i> 1</span></div>
    <div class="yendif-player-description indent">playpause=<span class="maroon">0 <i>(or)</i> 1</span></div>
    <div class="yendif-player-description indent">currenttime=<span class="maroon">0 <i>(or)</i> 1</span></div>
    <div class="yendif-player-description indent">progress=<span class="maroon">0 <i>(or)</i> 1</span></div> 
    <div class="yendif-player-description indent">duration=<span class="maroon">0 <i>(or)</i> 1</span></div> 
    <div class="yendif-player-description indent">volumebtn=<span class="maroon">0 <i>(or)</i> 1</span></div>   
    <div class="yendif-player-description indent">fullscreen=<span class="maroon">0 <i>(or)</i> 1</span></div>    
    <div class="yendif-player-description indent">embed=<span class="maroon">0 <i>(or)</i> 1</span></div> 
    <div class="yendif-player-description indent">share=<span class="maroon">0 <i>(or)</i> 1</span></div> 
    <div class="yendif-player-description indent">download=<span class="maroon">0 <i>(or)</i> 1</span></div>    
    <div class="yendif-player-description indent">playlistWidth=<span class="maroon">250</span></div>
    <div class="yendif-player-description indent">playlistHeight=<span class="maroon">125</span></div>
    <div class="yendif-player-description indent">playlistPosition=<span class="maroon">right <i>(or)</i> left</span></div>
    <div class="yendif-player-description indent">autoplaylist=<span class="maroon">0 <i>(or)</i> 1</span></div>    
    <div class="yendif-player-description indent">keyboard=<span class="maroon">0 <i>(or)</i> 1</span>]</div>
    <h3><?php _e( 'Adding Thumbnail Galleries to your page or posts', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h3>
    <h4><?php _e( 'Adding a playlist gallery', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifgallery type=<span class="maroon">playlist</span>]</div>
    <div class="yendif-player-spacer"></div>
    <h4><?php _e( 'Adding a video gallery from single playlist', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifgallery playlist=<span class="maroon">X</span>]</div>
    <div class="yendif-player-spacer"></div>    
    <h4><?php _e( 'Adding a video gallery from multiple playlists', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifgallery playlist=<span class="maroon">X,Y,Z</span>]</div>
    <div class="yendif-player-note">
		<?php _e( '<strong>Note : </strong>X,Y,Z from the above examples refer to the <a href="?page=yendif-player-playlists">PLAYLIST ID</a>.', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </div>
    <h4><?php _e( 'Adding a video gallery based on video ID(s)', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifgallery video=<span class="maroon">X,Y,Z</span>]</div>
    <div class="yendif-player-note">
		<?php _e( '<strong>Note : </strong>X,Y,Z from the above examples refer to the <a href="?page=yendif-player-media">MEDIA ID</a>.', YENDIF_PLAYER_PLUGIN_SLUG ); ?>
    </div>
    <h4><?php _e( 'Adding a latest videos gallery', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifgallery sort=<span class="maroon">latest</span>]</div>
    <h4><?php _e( 'Adding a popular videos gallery', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifgallery sort=<span class="maroon">popular</span>]</div>
    <h4><?php _e( 'Adding a featured videos gallery', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifgallery featured=<span class="maroon">0 <i>(or)</i> 1</span></div>
    <div class="yendif-player-description indent">sort=<span class="maroon">popular</span>]</div>
    <h4><?php _e( 'Complete list of short codes applicable for a video gallery', YENDIF_PLAYER_PLUGIN_SLUG ); ?></h4>
    <div class="yendif-player-description">[yendifgallery playlist=<span class="maroon">X</span></div>
    <div class="yendif-player-description indent">sort=<span class="maroon">latest <i>(or)</i> popular <i>(or)</i> data_added <i>(or)</i> a_z <i>(or)</i> z_a <i>(or)</i> random</span></div>
    <div class="yendif-player-description indent">featured=<span class="maroon">0 <i>(or)</i> 1</span></div>
    <div class="yendif-player-description indent">thumb_width=145</div>
    <div class="yendif-player-description indent">thumb_height=80</div>
    <div class="yendif-player-description indent">rows=3</div>
    <div class="yendif-player-description indent">columns=3</div>
    <div class="yendif-player-description indent">limit=8</div>
    <div class="yendif-player-description indent">title_limit=<span class="maroon">75</span></div>
    <div class="yendif-player-description indent">description=<span class="maroon">0 <i>(or)</i> 1</span></div>
    <div class="yendif-player-description indent">description_limit=150</div>
    <div class="yendif-player-description indent">views=<span class="maroon">0 <i>(or)</i> 1</span>]</div>
    <div class="yendif-player-spacer"></div>

</div>
