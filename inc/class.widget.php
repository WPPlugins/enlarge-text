<?php

/**
 * @package Enlarge Text
 */

function sjf_enlarge_text_widget_init() {
	register_widget( 'Text_Size_Widget' );
}
add_action( 'widgets_init', 'sjf_enlarge_text_widget_init' );

/**
 * Register our widget.
 */
class Text_Size_Widget extends WP_Widget {

	public function __construct() {
		
		parent::__construct(
	 		
			// Widget ID.
	 		'text_size_widget',
			
	 		// Widget name.
			esc_html__( 'Enlarge Text', 'sjf-enlarge-text' ),
			
			// Widget args.
			array(
				'description' => esc_html__( 'A text-sizer for readers who prefer larger text.', 'sjf-enlarge-text' ),
			)

		);

		add_action( 'admin_head', array( $this, 'admin_styles' ) );

	}

	/**
	 * Print some styles for our widget.
	 */
	public function admin_styles() {
		
		// Build a little hint to tell the dev where this is css is coming from.
		$added_by = __CLASS__ . '-' . __FUNCTION__;
		$added_by = sprintf( esc_html__( 'Added by %s', 'sjf-enlarge-text' ), $added_by );

		$out = <<<EOT

			.enlargetext-fieldset {
				border: 1px solid #ccc;
				border-radius: 3px;
				padding: 0 16px 9px;
				margin-bottom: 18px;
			}

			.enlargetext-legend {
				font-weight: 600;
			}

EOT;

		$out = "
			<!-- $added_by -->
			<style>$out</style>
		";

		echo $out;

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		
		$out = '';

		// The widget gives us the chance to change the labels for each size.
		$size_labels_str = '';

		// The widget gives us the chance to change the magnification for each size.
		$size_multipliers_str = '';

		// Grab the sizes from our defaults array.
		$sizes     = new SJF_Enlarge_Text_Sizes;
		$get_sizes = $sizes -> get();

		// For each size ...
		foreach( $get_sizes as $size_name => $size ) {

			// Sanitize the key for this size label.
			$size_label_key = $this -> alphanum_underscore_hyphen( $size_name );
			
			// Sanitize the value for this size label.
			$size_label_value = $this -> alphanum_underscore_hyphen_space( $size['front_end_label'] );

			// Did the user customize this size label?
			if( isset( $instance[ $size_name ] ) ) {

				// If so, defer to that.
				$size_label_value = $this -> alphanum_underscore_hyphen_space( $instance[ $size_name ] );

			}

			// Add the key/value pair for this size to the shortcode.
			$size_labels_str .= " $size_label_key='$size_label_value' ";	

			// Sanitize the key for the multiplier for this size.
			$size_multiplier_key = $this -> alphanum_underscore_hyphen( $size_name . '-multiplier' );

			// Sanitize the value for this multiplier for thsi size.
			$size_multiplier_value = $this -> num_period( $size['size_multiplier'] );

			// Did the user customize this size multiplier?
			$m_key = $size_name . '-multiplier';
			if( isset( $instance[ $m_key ] ) ) {

				// If so, defer to that.
				$size_multiplier_value = $this -> num_period( $instance[ $m_key ] );

			}

			// Add the key/value pair for this size to the shortcode.
			$size_multipliers_str .= " $size_multiplier_key='$size_multiplier_value' ";


			// One other thing while we're here: Is this size the default size?
			if( isset( $size['is_default'] ) ) {
				
				// If so, let's remember that -- we'll pass that to the shortcode.
				if( $size['is_default'] ) {
					$default_value = $size_name;
				}
			}

		}

		// Did the user specify a default size?
		if( isset( $instance['default_value'] ) ) {

			// If so, defer to that.
			$default_value = sanitize_text_field( $instance['default_value'] );

		}

		// Build the shortcode and parse it.
		$shortcode = do_shortcode( "[enlarge_text $size_labels_str $size_multipliers_str default_value='$default_value']" );

		// If the shortcode worked as expected, wrap it for widgetization output.
		if( ! empty( $shortcode ) ) {

			// Deal with the widget title.
			$title = apply_filters( 'widget_title', $instance['title'] );
			if( ! empty( $title ) ) {
				$title = $args['before_title'] . $title . $args['after_title'];
			}

			// Wrap the output as per theme standards.
			$out  = $args['before_widget'];
			$out .= $title;
			$out .= $shortcode;
			$out .= $args['after_widget'];

		}
		
		echo $out;

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		
		// Deal with the widget title.
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		// Grab the sizes from our registration array.
		$sizes     = new SJF_Enlarge_Text_Sizes;
		$get_sizes = $sizes -> get();

		// For each size...
		foreach( $get_sizes as $size_name => $size ) {
			
			// Let the user specify a label for this size.
			$instance[ $size_name ] = $this -> alphanum_underscore_hyphen_space( $new_instance[ $size_name ] );

			// Let the user specify a magnification for this size.
			$instance[ $size_name . '-multiplier' ] = $this -> num_period( $new_instance[ $size_name . '-multiplier' ] );
			
		}
	
		// The user can specify a default size.
		$instance['default_value'] = sanitize_text_field( $new_instance['default_value'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$prefix = strtolower( sanitize_html_class( SJF_ENLARGE_TEXT_PLUGIN_NAME ) );

		// Grab the sizes.
		$sizes     = new SJF_Enlarge_Text_Sizes;
		$get_sizes = $sizes -> get();

		/**
		 *  Make an input for the widget title.
		 */
		
		// Grab the current value for title.
		$title_value = __( 'New title', 'sjf-enlarge-text' );
		if ( isset( $instance['title'] ) ) {
			$title_value = $instance['title'];
		}

		// Build the input.
		$title_label = __( 'Title:', 'sjf-enlarge-text' );
		$title_name  = esc_attr( $this -> get_field_name( 'title' ) );
		$title_id    = esc_attr( $this -> get_field_id( 'title' ) );
		$title_input = "
			<p>
				<label for='$title_id'>$title_label</label> 
				<input class='widefat' id='$title_id' name='$title_name' type='text' value='$title_value'>
			</p>
		";
			
		/**
		 * Make an input for determining the default size.
		 */
		
		// Grab the current value for default size. 
		$default_value = 'small';
		if ( isset( $instance[ 'default_value' ] ) ) {
			$default_value = $instance[ 'default_value' ];
		}

		// Build the input.
		$default_value_options = '';
		foreach( $get_sizes as $size_name => $size ) {
			
			// Make an <option> for this size.
			$admin_label            = $size['admin_label'];
			$selected               = selected( $default_value, $size_name, FALSE );
			$default_value_options .= "<option $selected value='$size_name'>$admin_label</option>";
			
		}
		
		$default_value_label = __( 'Default Value:', 'sjf-enlarge-text' );
		$default_value_name  = esc_attr( $this -> get_field_name( 'default_value' ) );
		$default_value_id    = esc_attr( $this -> get_field_id( 'default_value' ) );
		$default_value_input = "
			<p>
				<label for='$default_value_id'>$default_value_label</label> 
				<select class='widefat' id='$default_value_id' name='$default_value_name'>		
					$default_value_options
				</select>
			</p>
		";

		/**
		 * Build inputs to control label & size for each size.
		 */
		
		// This will hold all of the size inputs.
		$size_inputs = '';

		// For each size...
		foreach( $get_sizes as $size_name => $size ) {

			// Grab the admin label for this size.
			$admin_label = $size['admin_label'];
			if( isset( $instance[ $size_name ] ) ) { $size_label = $instance[ $size_name ]; }

			// Grab the front end label for this size.
			$size_value = $size['front_end_label'];
			if( isset(  $instance[ $size_name ] ) ) {
				$size_value = $instance[ $size_name ];
			}

			// Build the atts for the front-end label input.
			$label_label     = esc_html__( 'Label:', 'sjf-enlarge-text' );
			$size_input_name = esc_attr( $this -> get_field_name( $size_name ) );
			$size_id         = esc_attr( $this -> get_field_id( $size_name ) );
			
			// Build the atts for the multiplier input.
			$multiplier_label      = esc_html__( 'Font Size in em units:', 'sjf-enlarge-text' );
			$size_multiplier_name  = esc_attr( $this -> get_field_name( $size_name . "-multiplier" ) );
			$size_multiplier_id    = esc_attr( $this -> get_field_id( $size_name . "-multiplier" ) );
			$size_multiplier_value = $size['size_multiplier'];
			if( isset( $instance[ $size_name . "-multiplier" ] ) ) {
				$size_multiplier_value = $instance[ $size_name  . "-multiplier" ];
			}
		
			// Each size gets a fieldset, containing two fields.
			$size_inputs .= "

				<fieldset class='$prefix-fieldset'>
					
					<legend  class='$prefix-legend'>$admin_label</legend>
					
					<p class='$prefix-input-wrap'>
						<label for='$size_id' class='$prefix-label'>$label_label</label> 
						<input class='widefat $prefix-input' id='$size_id' name='$size_input_name' type='text' value='$size_value'>
					</p>

					<p class='$prefix-input-wrap'>
						<label for='$size_multiplier_id' class='$prefix-label'>$multiplier_label</label> 
						<input class='widefat $prefix-input' id='$size_multiplier_id' name='$size_multiplier_name' type='text' value='$size_multiplier_value'>
					</p>

				</fieldset>
			";

		}

		$out  = $title_input;
		$out .= $default_value_input;
		$out .= $size_inputs;

		echo $out;
 
	}

	/**
	 * Sanitize a string to letters, numbers, _, -.
	 * 
	 * @param  string $string A string.
	 * @return string         A string, cleaned.
	 */
	private function alphanum_underscore_hyphen( $string ) {
		return preg_replace( '/[^a-zA-Z0-9-_]/', '', $string );
	}

	/**
	 * Sanitize a string to numbers, periods.
	 * 
	 * @param  string $string A string.
	 * @return string         A string, cleaned.
	 */
	private function num_period( $string ) {
		return filter_var( $string, FILTER_VALIDATE_FLOAT);
	}

	/**
	 * Sanitize a string to letters, numbers, _, spaces.
	 * 
	 * @param  string $string A string.
	 * @return string         A string, cleaned.
	 */
	private function alphanum_underscore_hyphen_space( $string ) {
		return preg_replace( '/[^a-zA-Z0-9-\s]/', '', $string );
	}

}