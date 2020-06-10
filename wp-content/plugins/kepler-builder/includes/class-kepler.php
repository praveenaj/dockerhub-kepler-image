<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Kepler
 * @subpackage Kepler/includes
 * @author     Revox <support@revox.io>
 */
class Kepler {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Kepler_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'kepler';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_filters();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->init_builder();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Kepler_Loader. Orchestrates the hooks of the plugin.
	 * - Kepler_i18n. Defines internationalization functionality.
	 * - Kepler_Admin. Defines all hooks for the admin area.
	 * - Kepler_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		
	
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kepler-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kepler-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kepler-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kepler-style-kit.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kepler-media.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kepler-demo.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kepler-elements.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kepler-page.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kepler-font.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kepler-map.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kepler-nav.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-kepler-public.php';

		/**
		 * The class responsible for defining all actions that occur in the builder interface
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'builder/class-kepler-builder.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-compiler-worker.php';

		$this->loader = new Kepler_Loader();

	}
	private function set_filters(){
		add_filter( 'content_save_pre', array( $this, 'on_save' ) , 10, 1 );
		// add_filter( 'default_content', array( $this, 'testRegex' ) );

	}


	public function on_save( $content ) {

		return $content;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Kepler_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Kepler_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Kepler_Admin( $this->get_plugin_name(), $this->get_version() );
		$stylekit = new Kepler_Stylekit();
		$kepler_elements = new Kepler_Elements();
		$kepler_pageUtil = new Kepler_PageUtil();
		$kepler_media = new Kepler_Media();
		$kepler_font = new Kepler_Font();
		$kepler_map = new Kepler_MapsUtil();
		$kepler_nav = new Kepler_Nav();
		$demo = new Kepler_Demo();

		$this->loader->add_action( 'edit_form_after_title', $plugin_admin, 'show_builder_link' );
		$this->loader->add_action( 'admin_print_footer_scripts-post.php', $plugin_admin, 'show_builder_link' );
		$this->loader->add_action( 'admin_print_footer_scripts-post-new.php', $plugin_admin, 'show_builder_link' );

		$this->loader->add_action( 'init', $plugin_admin, 'kepler_registery' );
		$this->loader->add_action( 'activated_plugin', $plugin_admin, 'kepler_builder_admin_redirect' );
		$this->loader->add_filter( 'rest_prepare_kp_elements', $plugin_admin, 'kepler_add_meta_to_json', 10, 3  );
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'kepler_append_element_type' );
		$this->loader->add_action( 'rest_prepare_page', $plugin_admin, 'kepler_append_compositions_to_pages', 10, 4 );
		$this->loader->add_action( 'rest_insert_page', $plugin_admin, 'kepler_set_composition_to_page', 10, 4 );
		$this->loader->add_action( 'rest_prepare_kp_block_composition', $plugin_admin, 'kepler_include_default_comp_in_compositions', 10, 4 );
		// AJAX functions
		$this->loader->add_action( 'wp_ajax_kepler_get_all_pages_content', $kepler_pageUtil, 'kepler_get_all_pages_content' );
		$this->loader->add_action( 'wp_ajax_kepler_get_page_content', $kepler_pageUtil, 'kepler_get_page_content' );
		$this->loader->add_action( 'wp_ajax_kepler_get_master_template', $kepler_pageUtil, 'kepler_get_master_template' );
		$this->loader->add_action( 'wp_ajax_kepler_set_page_content', $kepler_pageUtil, 'kepler_set_page_content' );
		$this->loader->add_action( 'wp_ajax_kepler_set_page_content_only', $kepler_pageUtil, 'kepler_set_page_content_only' );
		$this->loader->add_action( 'wp_ajax_kepler_set_style_string_only', $kepler_pageUtil, 'kepler_set_style_string_only' );
		$this->loader->add_action( 'wp_ajax_kepler_set_styleKit_css_only', $kepler_pageUtil, 'kepler_set_styleKit_css_only' );
		$this->loader->add_action( 'wp_ajax_kepler_set_local_css_only', $kepler_pageUtil, 'kepler_set_local_css_only' );
		$this->loader->add_action( 'wp_ajax_kepler_get_page_meta', $kepler_pageUtil, 'kepler_get_page_meta' );
		$this->loader->add_action( 'wp_ajax_kepler_set_page_meta', $kepler_pageUtil, 'kepler_set_page_meta' );
		$this->loader->add_action( 'wp_ajax_kepler_get_footer_content', $kepler_pageUtil, 'kepler_get_footer_content' );
		$this->loader->add_action( 'wp_ajax_kepler_get_header_content', $kepler_pageUtil, 'kepler_get_header_content' );
		$this->loader->add_action( 'wp_ajax_kepler_get_compositions_list', $kepler_pageUtil, 'kepler_get_compositions_list' );
		$this->loader->add_action( 'wp_ajax_kepler_get_composition', $kepler_pageUtil, 'kepler_get_composition' );
		$this->loader->add_action( 'wp_ajax_kepler_delete_composition', $kepler_pageUtil, 'kepler_delete_composition' );
		$this->loader->add_action( 'wp_ajax_kepler_set_composition', $kepler_pageUtil, 'kepler_set_composition' );
		$this->loader->add_action( 'wp_ajax_kepler_set_composition_content', $kepler_pageUtil, 'kepler_set_composition_content' );
		$this->loader->add_action( 'wp_ajax_kepler_set_composition_style_string', $kepler_pageUtil, 'kepler_set_composition_style_string' );
		$this->loader->add_action( 'wp_ajax_kepler_set_composition_style_css', $kepler_pageUtil, 'kepler_set_composition_style_css' );
		$this->loader->add_action( 'wp_ajax_kepler_set_composition_local_css', $kepler_pageUtil, 'kepler_set_composition_local_css' );
		$this->loader->add_action( 'wp_ajax_kepler_set_default_composition', $kepler_pageUtil, 'kepler_set_default_composition' );
		$this->loader->add_action( 'wp_ajax_kepler_import_single_page', $kepler_pageUtil, 'kepler_import_single_page' );
		$this->loader->add_action( 'wp_ajax_kepler_download_page_images', $kepler_pageUtil, 'kepler_download_page_images' );
		$this->loader->add_action( 'wp_ajax_kepler_import_pages', $kepler_pageUtil, 'kepler_import_pages' );
		$this->loader->add_action( 'wp_ajax_kepler_import_page', $kepler_pageUtil, 'kepler_import_page' );
		$this->loader->add_action( 'wp_ajax_kepler_import_stylekitObj', $kepler_pageUtil, 'kepler_import_stylekitObj' );
		$this->loader->add_action( 'wp_ajax_kepler_import_HFComposition', $kepler_pageUtil, 'kepler_import_HFComposition' );
		$this->loader->add_action( 'wp_ajax_kepler_import_composition', $kepler_pageUtil, 'kepler_import_composition' );

		$this->loader->add_action( 'wp_ajax_kepler_query_colorlovers', $plugin_admin, 'kepler_query_colorlovers' );

		$this->loader->add_action( 'wp_ajax_kepler_duplicate_element', $kepler_elements, 'kepler_duplicate_element' );
		$this->loader->add_action( 'wp_ajax_kepler_remove_element', $kepler_elements, 'kepler_remove_element' );

		$this->loader->add_action( 'wp_ajax_kepler_update_settings', $plugin_admin, 'kepler_update_settings' );

		$this->loader->add_action( 'wp_ajax_kepler_upload_image', $kepler_media, 'kepler_upload_image' );
		$this->loader->add_action( 'wp_ajax_kepler_delete_images', $kepler_media, 'kepler_delete_images' );
		$this->loader->add_action( 'wp_ajax_kepler_download_multi_images', $kepler_media, 'kepler_download_multi_images' );
		$this->loader->add_action( 'wp_ajax_kepler_download_single_image', $kepler_media, 'kepler_download_single_image' );
		$this->loader->add_action( 'wp_ajax_kepler_set_key_type', $kepler_media, 'kepler_set_key_type' );

		$this->loader->add_action( 'wp_ajax_kepler_get_server_time', $plugin_admin, 'kepler_get_server_time' );

		$this->loader->add_action( 'wp_ajax_kepler_get_adobe_typekit', $kepler_font, 'kepler_get_adobe_typekit' );
		$this->loader->add_action( 'wp_ajax_kepler_set_typekit_kit', $kepler_font, 'kepler_set_typekit_kit' );	

		// $this->loader->add_action( 'wp_ajax_kepler_get_all_stylekits', $stylekit, 'kepler_get_all_stylekits' );
		$this->loader->add_action( 'wp_ajax_kepler_download_style_kit', $stylekit, 'kepler_download_style_kit' );
		$this->loader->add_action( 'wp_ajax_kepler_update_stylekit', $stylekit, 'kepler_update_stylekit' );
		$this->loader->add_action( 'wp_ajax_kepler_get_stylekits', $stylekit, 'kepler_get_stylekits' );
		$this->loader->add_action( 'wp_ajax_kepler_get_stylekit_styles', $stylekit, 'kepler_get_stylekit_styles' );
		$this->loader->add_action( 'wp_ajax_kepler_switch_stylekit', $stylekit, 'kepler_switch_stylekit' );
		$this->loader->add_action( 'wp_ajax_kepler_restore_default_stylekit', $stylekit, 'kepler_restore_default_stylekit' );
		$this->loader->add_action( 'wp_ajax_kepler_remove_style_kit', $stylekit, 'kepler_remove_style_kit' );
		
		$this->loader->add_action( 'wp_ajax_kepler_get_demo_content', $demo, 'kepler_get_demo_content' );
		// background video
		$this->loader->add_action( 'wp_ajax_kepler_get_youtube_video', $kepler_media, 'kepler_get_youtube_video' );
		$this->loader->add_action( 'wp_ajax_kepler_get_vimeo_video', $kepler_media, 'kepler_get_vimeo_video' );
		
		//media
		$this->loader->add_action( 'wp_ajax_kepler_download_unsplash', $kepler_media, 'kepler_download_unsplash' );	
		$this->loader->add_action( 'wp_ajax_kepler_get_media_info', $kepler_media, 'kepler_get_media_info' );	
		$this->loader->add_action( 'wp_ajax_kepler_get_logo_info', $kepler_media, 'kepler_get_logo_info' );	
		$this->loader->add_action( 'wp_ajax_kepler_set_logo', $kepler_media, 'kepler_set_logo' );	
		
		// navigation
		$this->loader->add_action( 'wp_ajax_kepler_generate_nav', $kepler_nav, 'kepler_generate_nav' );	
		$this->loader->add_action( 'wp_ajax_kepler_get_nav', $kepler_nav, 'kepler_get_nav' );	
		$this->loader->add_action( 'wp_ajax_kepler_update_nav', $kepler_nav, 'kepler_update_nav' );	
		$this->loader->add_action( 'wp_ajax_kepler_add_nav_item', $kepler_nav, 'kepler_add_nav_item' );	
		$this->loader->add_action( 'wp_ajax_kepler_delete_nav_item', $kepler_nav, 'kepler_delete_nav_item' );	
		$this->loader->add_action( 'wp_ajax_kepler_update_nav_item_label', $kepler_nav, 'kepler_update_nav_item_label' );	
		$this->loader->add_action( 'wp_ajax_kepler_delete_nav', $kepler_nav, 'kepler_delete_nav' );	

		// welcome wizard
		$this->loader->add_action( 'wp_ajax_kepler_get_registration', $plugin_admin, 'kepler_get_registration' );
		$this->loader->add_action( 'wp_ajax_kepler_register_product', $plugin_admin, 'kepler_register_product' );
		
		$this->loader->add_action( 'wp_ajax_kepler_render_shortcode', $plugin_admin, 'kepler_render_shortcode' );
		$this->loader->add_action( 'wp_ajax_kepler_set_google_map_api', $kepler_map, 'kepler_set_google_map_api' );

		// WP Admin
		$this->loader->add_action( 'wp_ajax_kepler_enable_diagnostic', $plugin_admin, 'kepler_enable_diagnostic' );
		$this->loader->add_action( 'wp_ajax_kepler_get_wizard_status', $plugin_admin, 'kepler_get_wizard_status' );
		$this->loader->add_action( 'wp_ajax_kepler_set_wizard_status', $plugin_admin, 'kepler_set_wizard_status' );
		$this->loader->add_action( 'wp_ajax_kepler_set_trial_status', $plugin_admin, 'kepler_set_trial_status' );


	}



	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Kepler_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'kepler_public_init' );
		$this->loader->add_action( 'init', $plugin_public, 'define_shortcodes' );
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Kepler_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Initialize builder
	 */
	public function init_builder() {
		$plugin_builder = new Kepler_Builder( $this->get_plugin_name(), $this->get_version() );
	}

}
