(function ( $ ) {
	
	"use strict";

	$(function () {

		var page = $('#yendif-player-page').val(),
			media = mode = '';
		
		if (page === 'yendif-player-settings') {
			
			var $playerSizeElem = $( '.yendif-player-responsive', '.yendif-player' ),
				$fixedElements = $( '.yendif-player-row-fixed', '.yendif-player' ),
				$responsiveElements = $( '.yendif-player-row-responsive', '.yendif-player' ),
				$selectedItem = $( 'input:radio[name=responsive]:checked', '.yendif-player' ),
				mode = 'responsive';
				
			var togglePlayerSize = function ( _mode ) {
				mode = _mode;
				
				if ( mode === 'responsive' ) {
					$fixedElements.hide();
					$responsiveElements.show();					
				} else {
					$fixedElements.show();
					$responsiveElements.hide();					
				};
			};
			
    		$playerSizeElem.on( 'change', function() {													  
      			( mode === 'fixed' ) ? togglePlayerSize( 'responsive' ) : togglePlayerSize( 'fixed' );				
    		});
			
			( $selectedItem.val() == 0 ) ? togglePlayerSize( 'fixed' ) : togglePlayerSize( 'responsive' );
			
		} else if (page === 'yendif-player-media') {
			
			media = $( 'input:radio[name="type"]:checked', '.yendif-player' ).val();
			
			var loadMediaType = function ( _media ) {
				document.getElementById('yendif-player-row-youtube').style.display = 'none';
				document.getElementById('yendif-player-row-rtmp').style.display = 'none';
				document.getElementById('yendif-player-row-flash').style.display = 'none';
				document.getElementById('yendif-player-row-fallback').style.display = 'none';
				document.getElementById('yendif-player-row-mp4').style.display = 'none';
				document.getElementById('yendif-player-row-webm').style.display = 'none';
				document.getElementById('yendif-player-row-mp3').style.display = 'none';
				document.getElementById('yendif-player-row-wav').style.display = 'none';
				document.getElementById('yendif-player-row-ogg').style.display = 'none';
				document.getElementById('yendif-player-row-vtt').style.display = 'none';
				document.getElementById('yendif-player-row-views').style.display = 'none';
				
				media = _media;
				
				switch(media) {
					case 'youtube' :
						document.getElementById('yendif-player-row-youtube').style.display = '';
						document.getElementById('yendif-player-row-vtt').style.display = '';
						document.getElementById('yendif-player-row-views').style.display = '';
						break;
					case 'rtmp' :
						document.getElementById('yendif-player-row-rtmp').style.display = '';
						document.getElementById('yendif-player-row-flash').style.display = '';
						document.getElementById('yendif-player-row-fallback').style.display = '';
						document.getElementById('yendif-player-row-vtt').style.display = '';
						document.getElementById('yendif-player-row-views').style.display = '';
						break;
					case 'audio' :
						document.getElementById('yendif-player-row-mp3').style.display = '';
						document.getElementById('yendif-player-row-wav').style.display = '';
						document.getElementById('yendif-player-row-ogg').style.display = '';
						break;				
					default :
						document.getElementById('yendif-player-row-mp4').style.display = '';
						document.getElementById('yendif-player-row-webm').style.display = '';
						document.getElementById('yendif-player-row-ogg').style.display = '';
						document.getElementById('yendif-player-row-vtt').style.display = '';
						document.getElementById('yendif-player-row-views').style.display = '';
				};
				
			};
			
			$( 'input[name="type"]' ).on( 'change', function() {
			    media = $(this).val();
      			loadMediaType(media);				
    		});			
			
			loadMediaType( media );			
		};
		
    	$('.yendif-player-type').on( 'change', function() {											  
      		$( this ).parents( '.yendif-player-media-uploader' ).find( '.button' ).toggle();				
    	});
			
		// Uploading files
		var file_frame;			
		
		$('.yendif-player-upload-button').live( 'click', function( event )	{

    		event.preventDefault();			

    		// If the media frame already exists, reopen it.
    		if ( file_frame ) {
      			file_frame.close();
    		};

			var id = $( this ).attr( 'data-yendif-player-attachment' ),
				library = $( this ).attr( 'data-yendif-player-frame-library' );
				
			if ( library == 'ogg' ) {
				library = media;
			};
			
    		// Create the media frame.
    		file_frame = wp.media.frames.file_frame = wp.media( {
      			title : yendifplayer_locale.title,
      			button : {
        			text : yendifplayer_locale.button,
      			},
				library : {
                	type : library
                },
      			multiple: false  // Set to true to allow multiple files to be selected
    		});

    		// When an image is selected, run a callback.
    		file_frame.on( 'select', function() {
											  
      			var selection = file_frame.state().get( 'selection' );
				selection.map( function( attachment ) { 
      				attachment = attachment.toJSON(); 
					$( '#' + id ).val( attachment.url );
    			});
				
    		});

    		// Finally, open the modal
    		file_frame.open();
  		});
		
		// Validating forms
		$('#yendif-player-form').on( 'submit', function() {			
			var $_req = $( '#yendif-player-form' ).find( '.required' ),
				_err = false;
			
			$_req.removeClass( 'yendif-player-invalid' );
			
			$_req.each( function( index, el ) {
				
				var _id = this.id;
				
				if (page === 'yendif-player-settings') {
					
					switch ( mode ) {
						case 'responsive' :
							if ( /width|height/.test( _id ) ) return;
							break;
						case 'fixed' : 
							if ( /ratio/.test( _id ) ) return;
							break;
					}
					
				} else if ( page === 'yendif-player-media' ) {
					
					switch ( media ) {
						case 'video' :
							if ( /youtube|rtmp|flash|mp3/.test( _id ) ) return;
							break;
						case 'youtube' : 
							if ( /mp4|rtmp|flash|mp3/.test( _id ) ) return;
							break;
						case 'rtmp' :
							if ( /mp4|youtube|mp3/.test( _id ) ) return;
							break;
						case 'audio' :
							if ( /mp4|youtube|rtmp|flash/.test( _id ) ) return;
							break;
					}
					
				};
				
    			var _val = $( el ).val();
				
				if ( _val == '' ) {
					$( el ).addClass( 'yendif-player-invalid' );
					_err = true;
				};
				
			} );
			
			$_req.keyup( function() {

				var _val = $( this ).val();
				
    			if ( _val != '' ) {
					$( this ).removeClass( 'yendif-player-invalid' );
				};
				
			} );
			
			var _warned = false;
			if ( _err ) {
				$( "html, body" ).animate( { scrollTop: 0 }, "fast", function() {
					if ( _warned ) return;
					alert( yendifplayer_locale.required_alert );
					_warned = true;
				} );
				
			};
			
			return !_err;
		} );

	});

}(jQuery));