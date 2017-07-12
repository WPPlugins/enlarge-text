<?php

/**
 * @package Enlarge Text
 */

class SJF_Enlarge_Text_Sizes {

	/**
	 * Get our sizes, in order.
	 * 
	 * @return array An array of size arrays.
	 */
	public function get() {

		// Grab our sizes.
		$out = $this -> define();

		// Sort them in ascending order.
		$out = $this -> sort( $out );

		return $out;
		
	}

	/**
	 * Grab our size definitions.
	 * 
	 * @return array An array of size arrays.
	 */
	private function define() {

		$out = array();

		$prefix = __CLASS__ . '-' . __FUNCTION__;

		$small = array(

			// The font size for this size.
			'size_multiplier' => '1',
			
			// The clickable label for this size.
			'front_end_label' => esc_html__( 'M', 'sjf-enlarge-text' ),

			// The admin text for this size.
			'admin_label'     => esc_html__( 'Small', 'sjf-enlarge-text' ),

			// The order for this size.
			'order'           => 10,

			// Is this the default size if there is no cookie?
			'is_default'      => TRUE,

		);

		// The user can alter each of our sizes.
		$small = apply_filters( $prefix . '-small', $small );

		$medium = array(
			'size_multiplier' => '1.2',
			'front_end_label' => esc_html__( 'L', 'sjf-enlarge-text' ),
			'admin_label'     => esc_html__( 'Medium', 'sjf-enlarge-text' ),
			'order'           => 20,
		);
		$medium = apply_filters( $prefix . '-medium', $medium );

		$large = array(
			'size_multiplier' => '1.4',
			'front_end_label' => esc_html__( 'X', 'sjf-enlarge-text' ),
			'admin_label'     => esc_html__( 'Large', 'sjf-enlarge-text' ),
			'order'           => 30,
		);
		$large = apply_filters( $prefix . '-large', $large );

		$out['small']  =  $small;
		$out['medium'] = $medium;
		$out['large']  =  $large;

		// Allow the user to define his own sizes.
		$out = apply_filters( $prefix, $out );

		return $out;

	}

	/**
	 * Sort the sizes.
	 * 
	 * @param  array $sizes Our size arrays.
	 * @return array Our sizes, in order.
	 */
	private function sort( $sizes ) {
		
		uasort( $sizes, array( $this, 'sort_by_order' ) );

		return $sizes;

	}

	/**
	 * A callback for uasort();
	 * 
	 * @param  array $a A size.
	 * @param  array $b A size.
	 * @return integer The difference between two sizes.
	 */
	private function sort_by_order( $a, $b ) {
    	return $a['order'] - $b['order'];
	}

}