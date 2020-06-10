<?php
/**
 * kepler_theme WP Admin
 *
 * This is used for adding kepler_theme admin features which includes 
 * adding sidebar item, loading tab templates, settings and checking for
 * depedencies
 *
 * @since      1.0.0
 * @package    kepler_theme
 * @author     Revox <support@revox.io>
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}

/**
 * kepler_theme Settings panel in WP-Admin
 *
 * @since 1.0
 */
class kepler_theme_Admin {
	/**
	 * Constructor.
	 *
	 * @access  public
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'kepler_theme_admin_menu_setup' ) );
		add_action( 'admin_init',  array( $this, 'kepler_theme_admin_redirect' ) );

	}

	/*
	* Default Settings Page
	*/
	public function kepler_theme_admin_main_page() {	
		require_once get_template_directory() . '/inc/admin/getting_started.php';	
	}
	/*
	* Populate sidebar navigation items
	*/
	public function kepler_theme_admin_menu_setup() {
		$isEssentialsInstalled = class_exists('Kepler_Builder');
		if($isEssentialsInstalled){
			remove_menu_page('kepler_theme');
		}else{
			add_theme_page( 'Kepler Getting Started', 'Kepler Settings', 'edit_theme_options', 'kepler_theme', array( $this, 'kepler_theme_admin_main_page' ) );
			wp_enqueue_style( 'kepler_theme_admin_css', get_template_directory_uri() . '/inc/assets/kepler_theme_admin.css', array() );
		}
		
	}

	/*
	* When theme is activate redirect to default tab of kepler_theme Settings panel 
	*/
	public function kepler_theme_admin_redirect() {
		global $pagenow;
		$isEssentialsInstalled = class_exists('Kepler_Builder');
		if ( "themes.php" == $pagenow && is_admin() && isset( $_GET['activated'] ) ) {
			if($isEssentialsInstalled){
				//Essential Plugins installed
				wp_redirect('admin.php?page=kepler_builder_overview');
			}else{
				//Default Setting Page
				wp_redirect('themes.php?page=kepler_theme');
			}
		}
	}

}
