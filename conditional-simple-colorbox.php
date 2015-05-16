<?php

/**
 * The Conditional Simple Colorbox Plugin
 *
 * Add a Colorbox to footer of your site only when needed.
 *
 * @package Conditional_Simple_Colorbox
 * @subpackage Main
 */

/**
 * Plugin Name: Conditional Simple Colorbox
 * Plugin URI:  http://blog.milandinic.com/wordpress/plugins/conditional-simple-colorbox/
 * Description: Add a Colorbox to footer of your site only when needed.
 * Author:      Milan Dinić
 * Author URI:  http://blog.milandinic.com/
 * Version:     1.0
 * License:     GPL
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load Conditional Simple Colorbox plugin.
 *
 * Unload parent class when all plugins and child class
 * are loaded but before both classes are instantiated.
 *
 * @since 1.0
 */
function conditional_simple_colorbox_load() {
	if ( class_exists( 'Simple_Colorbox' ) ) {
		// Load child class
		require_once dirname( __FILE__ ) . '/inc/class-conditional-simple-colorbox.php';

		// Deinitialize parent class
		remove_action( 'plugins_loaded', 'simple_colorbox' );
	}
}
add_action( 'plugins_loaded', 'conditional_simple_colorbox_load', 1 );
