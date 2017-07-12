<?php

/**
 * @package Enlarge Text
 */

function sjf_enlarge_text_shortcode_init() {
	new SJF_Enlarge_Text_Shortcode;
}
add_action( 'init', 'sjf_enlarge_text_shortcode_init' );

class SJF_Enlarge_Text_Shortcode {

	public function __construct() {
		add_shortcode( 'enlarge_text', array( $this, 'out' ) );
	}

	public function out( $atts ) { 

		// Will hold the shortcode output.
		$out = '';

		// Will hold the args that we pass to our shortcode.
		$args = array();

		// Let's establish a value for which size is the default.
		$args['default_value'] = 'small';

		// The array of text sizes that our plugin defines.
		$sizes = new SJF_Enlarge_Text_Sizes;
		$get_sizes = $sizes -> get();

		// For each size...
		foreach( $get_sizes as $size_name => $size ) {
			
			// Grab the value for front-end label.
			$args[ $size_name ]                 = $size['front_end_label'];

			// Grab the value for font-size.
			$args[ $size_name . '-multiplier' ] = $size['size_multiplier'];

		}		

		// Parse the args provided by the user, into our shortcode defaults.
		$atts = shortcode_atts( $args, $atts, 'enlarge_text' );

		// For each size...
		foreach( $get_sizes as $size_name => $size ) {

			$is_default = 0;

			// This flag will tell us if we already found a default.
			if( ! isset( $user_defined ) ) {

				// Did the user want this to be the default size?
				if( $size_name == $atts['default_value'] ) {
					$is_default = 1;
					
					// A flag so we can stop checking for this.
					$user_defined = TRUE;
				
				// Did our plugin establish this as the default size?
				} elseif ( isset( $size['is_default'] ) ) {
					if( $size['is_default'] ) {
						$is_default = 1;
					}
				}

			}

			// An HTML ID for this link.
			$id = 'make_' . $size_name;

			// The size dictated by this link.
			$size_multiplier = $size['size_multiplier'];
			$m_key = $size_name . '-multiplier';
			
			// Did the user provide a custom font size for this link?
			if( isset( $atts[ $m_key ] ) ) {
				$size_multiplier = $atts[ $m_key ];
			}

			// The label for this link.
			$label = $atts[ $size_name ];

			// Draw the link.
			$out .= "
				<a class='sjf-enlarge_text-changer changer' id='$id' href='#' data-sjf-enlarge_text-size_multiplier='$size_multiplier' data-sjf-enlarge_text-is_default='$is_default'>
					$label
				</a>
			";

		}

		// If we have some output, wrap it.
		if( ! empty( $out ) ) {

			$label_text = esc_html__( 'Text Size:', 'sjf-enlarge-text' );
			$label      = "<span class='sjf_et_title'>$label_text</span>";

			$out = "
				$label
				<div id='textsize_wrapper'>$out</div>
			";

		}

		$out = apply_filters( __CLASS__ . '-' . __FUNCTION__, $out );

		return $out;

	}

}