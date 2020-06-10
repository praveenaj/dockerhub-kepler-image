<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kepler
 * @subpackage Kepler/public
 * @author     Revox <support@revox.io>
 */
class Kepler_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kepler_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kepler_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kepler_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kepler_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}

	public function kepler_public_init(){
		/*
			Appends 'kp_' class to body to activate all user defined styles.
			All user defined classes are namespaced by kp_
			ex: .kp_ .kp_ubo1
		 */
		add_filter( 'body_class', function( $classes ) {
		    return array_merge( $classes, array( 'kp_' ) );
		} );
	}

	public function define_shortcodes() {
		foreach (glob(plugin_dir_path(__FILE__) . '/shortcodes/*.php', GLOB_NOSORT) as $filename) {
			require_once $filename;
		}
	}
}