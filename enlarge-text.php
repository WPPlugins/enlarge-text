<?php

/**
 * @package Enlarge_Text
 * @version 2.0
 */

/*
Author: Scott Fennell
Author URI: www.scottfennell.org
Description: Give your users a widget to enlarge the text on your site.  Is there some text that you don't want to enlarge?  Just declare a size for it in pixels in your stylesheet and this plugin will not affect it.
License: GPLv2 or later
Plugin Name: Enlarge Text
Plugin URI: http://wordpress.org/extend/plugins/enlarge-text/
Text Domain: sjf-enlarge-text
Version: 2.0
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

// Define our plugin name.
define( 'SJF_ENLARGE_TEXT_PLUGIN_NAME', 'Enlarge Text' );

// Define the paths to our plugin folders.
define( 'SJF_ENLARGE_TEXT_FILE', __FILE__ );
define( 'SJF_ENLARGE_TEXT_PATH', trailingslashit( plugin_dir_path( SJF_ENLARGE_TEXT_FILE ) ) );
define( 'SJF_ENLARGE_TEXT_INC_PATH', SJF_ENLARGE_TEXT_PATH . 'inc/' );

/**
 * Grab the classes that compose our plugin.  It's kind of a lot of files for 
 * such a simple plugin, but the files themselves are all quite small.
 */

// Make a shortcode for outputting the text-sizer.
require_once( SJF_ENLARGE_TEXT_INC_PATH . 'class.shortcode.php' );

// Define our widget.  The widget front-end output actually is powered by the shortcode.
require_once( SJF_ENLARGE_TEXT_INC_PATH . 'class.widget.php' );

// Enqueue our assets.
require_once( SJF_ENLARGE_TEXT_INC_PATH . 'class.enqueue.php' );

// A bit of inline JS, rather than a new http request for a whole 'nuther JS file.
require_once( SJF_ENLARGE_TEXT_INC_PATH . 'class.js.php' );

// Define the options for text size.
require_once( SJF_ENLARGE_TEXT_INC_PATH . 'class.sizes.php' );

?>