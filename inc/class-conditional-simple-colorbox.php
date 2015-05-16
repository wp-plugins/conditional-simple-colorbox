<?php

/**
 * The Conditional Simple Colorbox Plugin
 *
 * Add a Colorbox to footer of your site only when needed.
 *
 * @package Conditional_Simple_Colorbox
 * @subpackage Class
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

/* Exit if no parent class */
if ( ! class_exists( 'Simple_Colorbox' ) ) return;

/**
 * Conditional Simple Colorbox loader.
 *
 * Enqueue Colorbox files only when needed on page to improve
 * performance by avoiding unnecessary external requests and
 * inline content.
 *
 * If Colorbox is needed only when galleries are used,
 * remove expensive search through post's content. 
 *
 * @since 1.0
 */
class Conditional_Simple_Colorbox extends Simple_Colorbox {
	/**
	 * Should files be enqueued.
	 *
	 * @var $enqueue
	 * @since 1.0
	 * @access public
	 */
	public $enqueue = false;

	/**
	 * Adds all the methods to appropriate hooks or shortcodes.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		// Load parent
		parent::__construct();

		// Remove action hooks used for enqueueing
		remove_action( 'wp_enqueue_scripts', array( $this, 'css'     ) );
		remove_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

		// Add filters to check is enqueueing needed
		add_filter( 'the_content',  array( $this, 'the_content'  ) );
		add_filter( 'post_gallery', array( $this, 'post_gallery' ) );

		// Load Colorbox files
		add_filter( 'wp_footer', array( $this, 'wp_footer' ) );
	}

	/**
	 * Load Colorbox files.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function wp_footer() {
		if ( $this->enqueue ) {
			$this->css();
			$this->scripts();
		}
	}

	/**
	 * Load Colorbox when there is a link to image in post's content.
	 *
	 * Note that it finds all links to images, even if they
	 * are from text and not from thumbnail.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $content Content of post.
	 * @return string $content Content of post.
	 */
	public function the_content( $content ) {
		if ( ! $this->enqueue ) {
			$num = preg_match_all( '#(://[^\s]+(?=\.(jpe?g|png|gif)))#i', $content, $matches );
			if ( $num > 0 ) {
				$this->enqueue = true;
			}
		}

		return $content;
	}

	/**
	 * Load Colorbox when [gallery] shortcode is used.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $output The gallery output.
	 * @return string $output The gallery output.
	 */
	public function post_gallery( $output ) {
		if ( ! $this->enqueue ) {
			$this->enqueue = true;
		}

		return $output;
	}
}

/**
 * Initialize Colorbox.
 *
 * Load class when all plugins are loaded
 * so that other plugins can overwrite it.
 */
function conditional_simple_colorbox_instantiate() {
	global $simple_colorbox;
	$simple_colorbox = new Conditional_Simple_Colorbox();
}
add_action( 'plugins_loaded', 'conditional_simple_colorbox_instantiate', 15 );
