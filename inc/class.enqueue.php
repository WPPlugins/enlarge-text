<?php

/**
 * @package Enlarge Text
 */

function sjf_enlarge_text_enqueue_init() {
	new SJF_Enlarge_Text_Enqueue;
}
add_action( 'init', 'sjf_enlarge_text_enqueue_init' );

class SJF_Enlarge_Text_Enqueue {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'jquery' ) );
	}

	/**
	 * Grab jQuery in order to power our text sizer links.
	 */
	function jquery() {
		wp_enqueue_script( 'jquery' );
	}

}