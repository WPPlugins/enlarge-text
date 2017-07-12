=== Enlarge Text ===
Contributors: scofennell@gmail.com
Tags: enlarge text, text sizer, text resize
Requires at least: 2.9
Tested up to: 4.3
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Give your users a widget to enlarge the text on your site.

== Description ==

Give your users a way to enlarge the text on your site.  Is there some text that you don't want to enlarge?  Just declare a size for it in pixels in your stylesheet and this plugin will not affect it.

== Installation ==

1. Upload `enlarge text` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add shortcode [enlarge_text] wherever you want the links to appear.

== Frequently Asked Questions ==

= What does this plugin do? =

It Gives your users a widget to enlarge the text on your site.

= All the text? =

It can't change text for which you declare a size in pixels.

= How does it output? =

Via widget or shortcode.  Both take arguments for the labels of each size and also the default text size when first visiting the page. For example:[enlarge_text small='S' medium='M' large='L' default_value='medium']

== Screenshots ==

1. See the menu here.  The plugin adds a "current" class to whichever link is currently active.  Style the links however you want to!

== Changelog ==

= 2.0 =
* Code cleanup.
* Shortcode/widget take args for font size, for each of the sizes.
* Size array is filterable for more/fewer/different size definitions.

= 1.4 =
* Added widget options for labels and default size.  Fixed jQuery conflict.

= 1.1 =
* Added widget.

= 1.0 =
* Initial release.

== Upgrade Notice ==

The current version of Enlarge Text requires WordPress 2.9 or higher. If you use older version of WordPress, you need to upgrade WordPress first.