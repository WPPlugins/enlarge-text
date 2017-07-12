<?php

/**
 * @package Enlarge Text
 */

function sjf_enlarge_text_js_init() {
	new SJF_Enlarge_Text_JS;
}
add_action( 'init', 'sjf_enlarge_text_js_init' );

class SJF_Enlarge_Text_JS {

	public function __construct() {
		add_action( 'wp_head', array( $this, 'out' ) );
	}

	/**
	 * Output our inline scripts.
	 */
	public function out() {

		$prefix = __CLASS__ . '-' . __FUNCTION__;

		/**
		 * Make a string to explain where the heck
		 * this JS is coming from.
		 */ 
		$added_by = sprintf( esc_html__( 'Added by %s', 'sjf-enlarge-text' ), SJF_ENLARGE_TEXT_PLUGIN_NAME );

		$out = <<<EOT
			<!-- $added_by -->
			<script>
		
				/**
				 * A jQuery plugin for our text sizer.
				 */
				jQuery( window ).load( function() {

					// This is the wrapper class for text sizer.
					var el = jQuery( '#textsize_wrapper' );

					jQuery( el ).sjfEnlargeText();

				});

				( function ( $ ) {

					$.fn.sjfEnlargeText = function( options ) {

						return this.each( function() {

							// Store the text sizer widget.
							var that = this;

							// Each of the text sizer links.
							var sizes = $( this ).find( '.sjf-enlarge_text-changer' );

							// The cookie remembers which size you selected.
							var cookieName = 'sjf-enlarge_text-size';

							// The size dictated by the cookie.
							var cookieSize = getCookie();

							// Will store the link that gets used if no cookie is present.
							var defaultLink = '';
							
							// For each sizer link...
							jQuery( sizes ).each( function( k, v ) {
							
								// If the cookie is not telling us a size...
								if( cookieSize == false ) {

									// Is this the default size?
									var isDefault = jQuery( this ).data( 'sjf-enlarge_text-is_default' );
									
									// If so, use it.
									if( isDefault == 1 ) {
									
										defaultLink = jQuery( this );
										return;

									}
								
								// If there is a cookie...
								} else {

									// Is this the size requested by the cookie?
									var sizeMultiplier = jQuery( this ).data( 'sjf-enlarge_text-size_multiplier' );
									
									// If so, use it.
									if( sizeMultiplier == cookieSize ) {
									
										defaultLink = jQuery( this );
										return;

									}

								}

							});
							
							/**
							 * Set the cookie with the active link.
							 *
							 * @param {object} The jQuery selection for the active link.
							 */
							function setCookie( activeLink ) {
								
								// Read the link and grab what size it dictates.
								var size = getSize( activeLink );
								if( typeof size == 'undefined' ) { return false; }

								// Make the size a string.
								//size = size.toString( 10 );

								// Build a date thing, to determine cookie expiration.
								var d = new Date();
							    d.setTime( d.getTime() + ( 1 * 24 * 60 * 60 * 1000 ) );
							    d.toUTCString();
    							var expires = 'expires=' + d;

    							// The cookie is good for our entire domain.
    							var path = 'path=/';

    							// Bake the cookie.
								document.cookie = cookieName + '=' + size + '; ' + expires + '; ' + path;

							}

							/**
							 * Grab the cookie.
							 *
							 * @return {string} The cookie for our plugin.
							 */
							function getCookie() {

								var out = false;

								// Dig into the cookie string.
							    var cookies = document.cookie.split( ';' );
   								
							    // For each cookie...
   								jQuery.each( cookies, function() {

   									// Break it into chunks at each equal sign.
   									var cookie = this.split( '=' );
   									
   									// Grab the cookie name.
   									var thisCookieName = cookie[0].trim();
   								
   									// If this is the cookie that pertains to our plugin...
   									if( thisCookieName == cookieName ) {
   									
   										// Grab it.
   										//console.log( cookie[1] );
   										out = cookie[1];
   										return;

   									}
	   								
   								});
							
								return out;

							}

							/**
							 * Change the font size based on the active link.
							 *
							 * @param {whichLink} A jQuery selection of one of our text-changer links.
							 */
							function setSize( whichLink ) {
								jQuery( 'body' ).css( 'fontSize', getSize( whichLink ) + 'em' );
							}

							/**
							 * Determine the font size dicated by the active link.
							 *
							 * @param {object} A jQuery selection of one of our text-changer links.
							 * @return {string} The font size dictated by the link.
							 */
							function getSize( whichLink ) {
								var out = jQuery( whichLink ).data( 'sjf-enlarge_text-size_multiplier' );
								return out;
							}
	
							/**
							 * Toggle the classes for the active link.
							 *
							 * @param {activeLink} The link that the user has chosen.
							 */
							function applyClass( activeLink ) {

								// Deactivate any links that are currently active.
								jQuery( sizes ).removeClass( 'sjf_et_active active' );

								// Apply the active classes to the active link.
								jQuery( activeLink ).addClass( 'sjf_et_active active' );

							}

							/**
							 * Fire up our plugin -- apply the classes, size the text. 
							 *
							 * @param {object} The jQuery selection for the default link.
							 */
							function setup( defaultLink ) {
								applyClass( defaultLink );
								setSize( defaultLink );
							}

							/**
							 * A click handler for our links.
							 */
							jQuery( that ). find( '.changer' ).on( 'click', function( event ) {
								
								// Don't navigate the page when the links are clicked.
								event.preventDefault();

								// Run our functions when the links are clicked.
								applyClass( this );
								setSize( this );
								setCookie( this );
							
							});

							setup( defaultLink );

						});
				
					}

				}( jQuery ) );	

			</script>

EOT;

		$out = apply_filters( $prefix, $out );

		echo $out;

	}

}