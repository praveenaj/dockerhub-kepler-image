<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}

/**
 * Manage header and footer and initializes the main layout
 * This is the main entry point for the kepler_theme theme.
 *
 * @since 1.0
 */
class kepler_theme_Layout {
	
	protected $loader;

	public $footer;
	
	public $header;

	public static $instance = null;

	private function __construct() {

		$this->load_dependencies();
	}

	private function load_dependencies() {
		include_once get_template_directory() . '/inc/class-kepler-theme-footer.php';
		include_once get_template_directory() . '/inc/class-kepler-theme-header.php';
		include_once get_template_directory() . '/inc/class-kepler-theme-layout-loader.php';

		$this->loader = new kepler_theme_Layout_Loader();
		$this->footer = new kepler_theme_Footer();
		$this->header = new kepler_theme_Header();
	}
	
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new kepler_theme_Layout();
		}
		return self::$instance;
	}

}

