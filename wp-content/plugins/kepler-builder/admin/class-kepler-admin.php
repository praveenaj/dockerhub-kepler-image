<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 * @author     Revox <support@revox.io>
 */
class Kepler_Admin {

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


	private $adminPages = array('kepler_builder', 'kepler_builder_install', 'kepler_builder_overview', 'kepler_builder_settings', 'kepler_builder_help');

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since			1.0.0
	 * @param			string    $plugin_name       The name of this plugin.
	 * @param			string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_menu', array( $this, 'kepler_builder_admin_menu_setup' ) );
		add_action('admin_footer', array( $this, 'kepler_builder_admin_custom_footer') );
		add_action( 'admin_init', array( $this, 'kepler_builder_disallowed_admin_pages') );
		add_filter( 'admin_body_class', array( $this,'kepler_builder_admin_body_class') );
	}
	/*
	* Default tab of Admin panel	
	*/
	public function kepler_builder_admin_main_page() {	
		require_once plugin_dir_path( __FILE__ ) . 'partials/getting_started.php';	
	}
	/*
	* Dependency activation tab of Admin panel	
	*/
	public function kepler_builder_admin_plugin_page() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/plugins.php';	
	}

	/*
	* Register theme 
	*/
	public function kepler_builder_admin_settings_page() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/settings.php';	
	}

	/*
	* Overview 
	*/
	public function kepler_builder_admin_overview_page() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/overview.php';	
	}

	/*
	* Help 
	*/
	public function kepler_builder_admin_help_page() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/help.php';	
	}

	public function kepler_builder_admin_menu_setup(){
		$active_theme = wp_get_theme();
		add_menu_page( 'Kepler Admin', 'Kepler', 'manage_options', 'kepler_builder_admin', array( $this, 'kepler_builder_admin_main_page' ), 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PHBhdGggZmlsbD0iIzlFQTNBOCIgZD0iTTEyLjUgMi42bC0uNS0uM2EuNi42IDAgMDAtLjYgMEwyLjIgMy45bC0uNC4xLS40LjQtLjIuNC0uMS41VjE1bC4xLjUuMi40LjMuMy41LjIgOS4xIDEuM2guMmwuNC0uMS41LS4zLjQtLjUuMS0uNlYzLjZsLS4xLS42LS4zLS40ek0xNS45IDRhMiAyIDAgMDAtLjQtLjZsLS42LS40aC0uNXYxaC4ybC4yLjEuMi4zVjE1LjZsLS4yLjItLjIuMWgtLjJ2MWwuNS0uMS42LS40LjQtLjZWNC43IDR6TTE4LjkgNWwtLjQtLjVjLS4yLS4yLS4zLS4zLS42LS4zLS4xLS4yLS4zLS4yLS40LS4ydjFoLjJsLjIuMS4xLjJ2OS4ybC0uMS4yLS4yLjFoLS4ydjFsLjUtLjEuNi0uMy40LS41LjEtLjZWNS42bC0uMi0uNnoiLz48L3N2Zz4=',1);
		if($active_theme->get( 'TextDomain' ) != 'kepler_theme'){
			add_submenu_page( 'kepler_builder_admin_no_theme', 'Essentials', 'Essentials', 'edit_posts', 'kepler_builder_admin', array( $this, 'kepler_builder_admin_main_page' ));

		}else{
			add_submenu_page( 'kepler_builder_admin', 'Overview', 'Overview', 'edit_posts', 'kepler_builder_overview', array( $this, 'kepler_builder_admin_overview_page' ));
			add_submenu_page( 'kepler_builder_admin', 'Install Plugins', 'Install Plugins', 'edit_posts', 'kepler_builder_install', array( $this, 'kepler_builder_admin_plugin_page' ));
			add_submenu_page( 'kepler_builder_admin', 'Settings', 'Settings', 'edit_posts', 'kepler_builder_settings', array( $this, 'kepler_builder_admin_settings_page' ));
			add_submenu_page( 'kepler_builder_admin', 'Help', 'Help', 'edit_posts', 'kepler_builder_help', array( $this, 'kepler_builder_admin_help_page' ));
			remove_submenu_page('kepler_builder_admin','kepler_builder_admin');
			remove_submenu_page('kepler_builder_admin','kepler_builder_admin_no_theme');
		}
		$this->enqueue_styles();
		$this->enqueue_scripts();
	}

	public function kepler_builder_admin_redirect(){
		global $pagenow;

		$active_theme = wp_get_theme();

		$isEssentialsInstalled = ($active_theme->get( 'TextDomain' ) == 'kepler_theme');

		$redirectToPage = $isEssentialsInstalled ? 'kepler_builder_overview' : 'kepler_builder_admin';

		if($pagenow == 'plugins.php') {
			wp_redirect( admin_url( '/admin.php?page=' . $redirectToPage), 301 );
			exit;
		}
	}


	/*
	* Append kepler_theme_admin class to wp admin body
	*/
	function kepler_builder_admin_body_class( $classes ) {
		global $pagenow;

		$bodyClass = "$classes";

		if($pagenow == 'admin.php' &&
			isset($_GET['page']) && 
			in_array($_GET['page'], $this->adminPages)){
			$bodyClass .= " kepler-builder-plugin-page-styles";
		} 

		$wizardRan = get_option('kepler_wizard_ran');

		if($wizardRan != '1') {
			$bodyClass .= " kepler_wizard_not_run";
			
		}
		
		return $bodyClass;
		
	}

	/**
	 * Appends a custom footer for all kepler_theme pages in WP Admin
	 */

	public function kepler_builder_admin_custom_footer() {

		global $pagenow;

		if($pagenow == 'admin.php' && 
			isset($_GET['page']) && 
			in_array($_GET['page'], $this->adminPages)){
			echo '<ul class="an-footer-links">
				<li class="an-footer-link-item">
					<a href="#" target="_blank" rel="noopener noreferrer" class="an-footer-link" title="kepler_theme Plugin version">Kepler Theme Version 1.0.0</a>
				</li>
				<li class="an-footer-link-item">
					<a href="'.esc_url("https://help.kepler.app/").'" target="_blank" rel="noopener noreferrer" title="kepler_theme Terms and Conditions" class="an-footer-link">Community</a>
				</li
				><li class="an-footer-link-item">
					<a href="'.esc_url("https://help.kepler.app/knowledgebase/privacy-policy/").'" rel="noopener noreferrer" title="kepler_themes\'s Privacy Policy" class="an-footer-link">Privacy</a>
				</li>
				<li class="an-footer-link-item">
					<a href="'.esc_url("https://help.kepler.app/knowledgebase").'" title="Visit kepler_theme Community" class="an-footer-link">Knowledge Base</a>
				</li>
			</ul><script>window.kepler_theme_ADMIN_AJAX_URL="'. admin_url('admin-ajax.php'). '"</script>';
		} else {
			return;
		}

	}
	/**
	 * Checks if a page is not kepler_theme default tab in WP Admin
	 * @return bool
	 */
	public function kepler_builder_isNotOverview($page) {

		$isEssentialsInstalled = class_exists('Kepler_Builder');
		$redirectToPage = $isEssentialsInstalled ? 'kepler_builder_overview' : 'kepler_theme';

		return $page != $redirectToPage;
	}

	/**
	 * Allow or disallow access to a kepler_theme tab in WP admin. 
	 * If the pre-requisites are not met redirect to default tab
	 */
	public function kepler_builder_disallowed_admin_pages() {
		global $pagenow;

		$active_theme = wp_get_theme();

		$isEssentialsInstalled = ($active_theme->get( 'TextDomain' ) != 'kepler_theme');

		$redirectToPage = $isEssentialsInstalled ? 'kepler_builder_overview' : 'kepler_builder_admin';

		$wizardNotRan = get_option('kepler_wizard_ran') != '1';

		$notOverview = array_filter($this->adminPages,  array( $this,'kepler_builder_isNotOverview') );


		if($pagenow == 'admin.php' && 
			isset($_GET['page']) && 
			in_array($_GET['page'], $notOverview) && 
			$wizardNotRan) {

			wp_redirect( admin_url( '/admin.php?page=' . $redirectToPage), 301 );
			exit;

		}
	}
	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/kepler_builder_admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kepler_builder_admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Checks if Gutenberg plugin is active
	 *
	 * @since			1.0.0
	 */
	function is_gutenberg_page() {
		if ( function_exists( 'is_gutenberg_page' ) &&
						is_gutenberg_page()
		) {
				// The Gutenberg plugin is on.
				return true;
		}
		$current_screen = get_current_screen();
		if ( method_exists( $current_screen, 'is_block_editor' ) &&
						$current_screen->is_block_editor()
		) {
				// Gutenberg page on 5+.
				return true;
		}
		return false;
	}
	/**
	 * Show link to open builder in page editor
	 *
	 * @since		1.0.0
	 */

	public function show_builder_link($post){
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/preview-buttons.css', array(), $this->version, 'all' );
		
		global $post;
		$scr = get_current_screen();
		if( 'page' !== $scr->post_type ) {
			return;
		}
		$pageId = get_the_ID();
		$url = add_query_arg('kepler_builder', '1', get_permalink($pageId));
		$button = '<a href="'.esc_url($url).'" target="_blank" class="btn-kepler-builder-open default logo-icon">Open Editor</a>';
		
		if($post->post_status != "publish"){
			$button = '<a href="#" class="btn-kepler-builder-open default-disabled logo-icon">Open Editor</a>';
		}

		if($this->is_gutenberg_page()) { 
			$button = '<a href="'.esc_url($url).'" target="_blank" class="btn-kepler-builder-open default logo-icon guttern">Open Editor</a>';
			$button = $button . '<span class="btn-guttern-preview"><span>Preview:</span> <a href="'.get_permalink($pageId).'" target="_blank" class="components-external-link edit-post-post-link__link">'.get_permalink($pageId).'</a></span>';
			if($post->post_status != "publish"){
				$button = '<a href="#" class="btn-kepler-builder-open default-disabled guttern-disabled logo-icon">Open Editor</a>';
			}
		?>
		<script type="text/javascript">
		jQuery( window ).load( function() {
			var toolbar = jQuery( '.edit-post-header-toolbar' );

			if (toolbar) {
				toolbar.append( '<?php echo $button; ?>' );
			}

			// update kepler button state when page is saved
			var subscribe = wp.data.subscribe;
			var initialPostStatus = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'status' );
			var btn = $('.btn-kepler-builder-open')

			if(initialPostStatus == 'auto-draft') {
				btn.addClass('disabled')
				
				subscribe( function() {
					var currentPostStatus = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'status' );
					if ( 'publish' === currentPostStatus ||  'draft' === currentPostStatus) {
						btn.removeClass('disabled')
					}
				});
			}
		} );
		</script>
		<?php
		} else {
			$screen = get_current_screen();
			// editing a page
			if ($screen->post_type == 'page') {
				$isRichEditingEnabled = get_user_option( 'rich_editing' ) == 'true';
				if ($isRichEditingEnabled) {
					echo $button;
				}
			}
		}
	}

	/**
	 * Add POST type kp_element meta to API
	 *
	 * @since    1.0.0
	*/
	public function kepler_add_meta_to_json($data, $post, $request){

		$response_data = $data->get_data();

		if ( $request['context'] !== 'view' || is_wp_error( $data ) ) {
		    return $data;
		}
		$kp_element_data = get_post_meta( $post->ID, 'kp_data', true );
		$kp_element_json_data = get_post_meta( $post->ID, 'kp_element_json_data', true );
		$kp_element_scale = get_post_meta( $post->ID, 'kp_element_scale', true );
		$kp_element_template = get_post_meta( $post->ID, 'kp_template', true );
		$kp_element_allow_duplicate = get_post_meta( $post->ID, 'kp_element_allow_duplicate', true );
		$kp_element_theme = get_post_meta( $post->ID, 'kp_element_theme', true );
		$kp_element_user_created = get_post_meta( $post->ID, 'kp_element_user_created', true );

		if(empty($kp_element_data)){
		    $kp_element_data = '';
		}
		if(empty($kp_element_allow_duplicate)){
		    $kp_element_allow_duplicate = false;
		}
		if(empty($kp_element_theme)){
		    $kp_element_theme = '';
		}

		if($post->post_type == 'kp_elements'){
		    $response_data['kp_element_meta'] = array(
		        'kp_shortcode' => $kp_element_data,
		        'kp_element_theme' => $kp_element_theme,
				'kp_element_allow_duplicate' => $kp_element_allow_duplicate,
				'kp_element_template' => $kp_element_template,
				'kp_element_user_created' => $kp_element_user_created,
				'kp_element_json_data'=>$kp_element_json_data,
				'kp_element_scale'=>$kp_element_scale
		    );
		}

		$data->set_data( $response_data );

		return $data;
	}

	/**
	 * Add POST type kp_element meta to API
	 *
	 * @since    1.0.0
	*/
	public static function registerElementCategories() {
		//SET ELEMENTS CATEGORY ARGS
		$kp_element_category_args = array(
			'labels' => array(
				'name'              => _x( 'Categories', 'taxonomy general name', 'textdomain' ),
				'singular_name'     => _x( 'Catogory', 'taxonomy singular name', 'textdomain' ),
				'search_items'      => __( 'Search Categories', 'textdomain' ),
				'all_items'         => __( 'All Categories', 'textdomain' ),
				'parent_item'       => __( 'Parent Category', 'textdomain' ),
				'parent_item_colon' => __( 'Parent Category:', 'textdomain' ),
				'edit_item'         => __( 'Edit Category', 'textdomain' ),
				'update_item'       => __( 'Update Catogory', 'textdomain' ),
				'add_new_item'      => __( 'Add New Catogory', 'textdomain' ),
				'new_item_name'     => __( 'New Catogory Name', 'textdomain' ),
				'menu_name'         => __( 'Category', 'textdomain' ),
			),
			'hierarchical' => true,
			'show_in_rest' => true,
			'show_in_menu' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'kp_element_category' )
		);
		//REGISTER ELEMENTS CATEGORY
		register_taxonomy('kp_element_category', 'kp_elements',$kp_element_category_args );
	}

	/**
	 * Register Custom Post for Builder
	 *
	 * @since    1.0.0
	*/
	public function kepler_registery(){
		
		self::registerElementCategories();
		//SET ELEMENTS ARGS
		$kp_elements_args = array(
			'labels' => array(
			'name' => __( 'Kepler Elements' ),
			'singular_name' => __( 'Kepler Element' )
			),
			'show_in_menu' => false,
			'public' => true,
			'show_in_rest' => true,
			'has_archive' => false,
			'taxonomies'          => array( 'kp_element_category' ),
			'supports'			=> array(
				'title','editor','custom-fields'
			)
		);
		//REGISTER ELEMENTS
		register_post_type( 'kp_elements',$kp_elements_args);

		 //Blocks
		$kp_blocks_arg  = array(
	      'labels' => array(
	        'name' => __( 'Kepler Blocks' ),
	        'singular_name' => __( 'Kepler Blocks' )
	      ),
				'show_in_rest' => true,
				'show_in_menu' => false,				
	      'public' => true,
	      'has_archive' => false,
	    );
		register_post_type( 'kp_blocks',$kp_blocks_arg);

		 //Stylekits
		$kp_stylekit_arg  = array(
	      'labels' => array(
	        'name' => __( 'Kepler Stykits' ),
	        'singular_name' => __( 'Kepler Stylekit' )
	      ),
	      'show_in_rest' => true,
				'show_in_menu' => false,
	      'public' => true,
	      'has_archive' => false,
	    );
		register_post_type( 'kp_stylekit',$kp_stylekit_arg);

		$kp_localstyle_arg  = array(
			'labels' => array(
			  'name' => __( 'Kepler Local Styles' ),
			  'singular_name' => __( 'Kepler Local Style' )
			),
			'show_in_rest' => true,
			'show_in_menu' => false,
			'public' => true,
			'has_archive' => false,
			'supports' => array(
				'title',
				'editor',
				'revisions'
			  )
		  );
		  register_post_type( 'kp_local_style',$kp_localstyle_arg);

		$kp_footer_blocks_arg  = array(
	      'labels' => array(
	        'name' => __( 'Kepler Footer Blocks' ),
	        'singular_name' => __( 'Kepler Footer Block' )
	      ),
	      'show_in_rest' => true,
				'show_in_menu' => false,
	      'public' => true,
	      'has_archive' => false
	    );
		register_post_type( 'kp_footer_blocks',$kp_footer_blocks_arg);

		$kp_header_blocks_arg  = array(
	      'labels' => array(
	        'name' => __( 'Kepler Header Blocks' ),
	        'singular_name' => __( 'Kepler Header Block' )

	      ),
	      'show_in_rest' => true,
				'show_in_menu' => false,				
	      'public' => true,
	      'has_archive' => false,
	    );

		register_post_type( 'kp_header_blocks',$kp_header_blocks_arg);


		// header/footer combination posts. stores header,
		// footer post ids in custom fields
		$kp_block_comp_arg  = array(
	      'labels' => array(
	        'name' => __( 'Kepler Block Composition' ),
	        'singular_name' => __( 'Kepler Block Composition' )

	      ),
	      'show_in_rest' => true,
				'show_in_menu' => false,				
	      'public' => true,
	      'has_archive' => false,
	      'supports' => array(
			  'title',
			  'editor',
			  'excerpt',
			  'thumbnail',
			  'custom-fields',
			  'revisions'
			)
	    );

		register_post_type( 'kp_block_composition',$kp_block_comp_arg);

		//$this->kepler_rest_prepare_custom_posts();

		$kp_local_styles_arg  = array(
			'labels' => array(
			  'name' => __( 'Kepler Local Styles' ),
			  'singular_name' => __( 'Kepler Local Styles' )
  
			),
			'show_in_rest' => true,
				  'show_in_menu' => false,				
			'public' => true,
			'has_archive' => false,
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'custom-fields',
				'revisions'
			  )
		  );
  
		  register_post_type( 'kp_local_styles',$kp_local_styles_arg);
	}

	/**
	 * Get colors from Color Lovers API
	 *
	 * @since    1.0.0
	*/
	public function kepler_query_colorlovers(){
		$params = $_POST['params'];
		$response = file_get_contents('http://www.colourlovers.com/api/palettes/top?format=json&'.$params);
		echo $response;
		wp_die();
	}

	/**
	 * SET Builder settings
	 *
	 * @since    1.0.0
	*/
	public function kepler_update_settings(){
		$user_id = get_current_user_id();
		$settings = $_POST['settings'];

		update_user_meta($user_id, "kepler_builder_settings", wp_strip_all_tags(json_encode($settings)));

		$return = array(
			'message'	=> 'saved',
		);
		wp_send_json_success($return);
	}

	/**
	 * SET Builder settings
	 *
	 * @since    1.0.0
	*/
	public function kepler_get_server_time(){
		wp_send_json_success(current_time( 'mysql' ));
	}

	/** @TODO
	 * SET Builder settings
	 * @access		private
	 * @since			1.0.0
	*/
	function kepler_expose_meta_fields($data,$post,$request){
	    $metaFields = (array) get_post_meta( $post->ID );
		$kpMetaFields = array_filter($metaFields, function($key){
			return (0 === strpos($key, 'kp_'));
		},ARRAY_FILTER_USE_KEY);
		if($data->data['meta'] && is_array($data->data['meta'])){
			$data->data['meta'] = array_merge($data->data['meta'], $kpMetaFields );
		}
	    return $data;
	}

	/** @TODO
	 *  Assigns all the available meta fields to each kp_* custom posts in wp-api
	 * @scope private
	 * @since    1.0.0
	*/
	function kepler_rest_prepare_custom_posts(){
		global $wp_post_types;

		$kpCustomPosts = array_filter($wp_post_types, function($key){
			return (0 === strpos($key, 'kp_'));
		},ARRAY_FILTER_USE_KEY);

		foreach ($kpCustomPosts as $key => $value) {
			$filter = 'rest_prepare_' . $key; // ex: rest_prepare_kp_custom_colors
			add_filter($filter, array($this,'kepler_expose_meta_fields'), 10, 3);
		}
	}
	 
	 public function kepler_render_shortcode() {
		$shortcode = $_POST['shortcode'];
		if(shortcode){
			$shortcode = esc_html($shortcode);
			$shortcode = rawurldecode($shortcode);
			$rendered = array(
				'rendered' => do_shortcode($shortcode),
			);
			wp_send_json_success($rendered);
		}
		else{
			$return = array(
				'message'=>"shortcode error",
			);
			wp_send_json_error($return);
			return;
		}
	}

	/**
	 * GET element category for POST, used in kp_elements
	 * Showing elements category list in builder
	 * @since    1.0.0
	*/
	function kepler_get_element_category( $object ) {
		//get the id of the post object array
		$post_id = $object['id'];
		$term = ''; 

		$terms = get_the_terms($post_id, 'kp_element_category' );
		if ($terms && ! is_wp_error($terms)) :
			$tslugs_arr = array();
			$term = $terms[0];
			$term = $term->slug;
		endif;
		return $term;
	}
	
	 /**
	 * GET element RAW shortcode for POST, used in kp_elements
	 * @param			type	$var Post 
	 * @access		private 
	 * @return		$var	shortcode
	 * @since			1.0.0
	*/
	function kepler_get_element_shortcode( $object ) {
		return  $object["content"]["raw"];
	}

	/**
	 * Register fields for REST API kp_elements
	 *
	 * @since    1.0.0
	*/
	public function kepler_append_element_type() {
		register_rest_field( 'kp_elements', 'kp_element_type', array(
				'get_callback'    => array($this, 'kepler_get_element_category'),
				'schema'          => null,
		));
		
		register_rest_field( 'kp_elements', 'content_shortcode', array(
			'get_callback'    => array($this, 'kepler_get_element_shortcode'),
			'schema'          => null,
		));
	}

	// Sets kepler_composition_id when Page updated/created
	public function kepler_set_composition_to_page($post, $request, $create){
		if ($request['kepler_composition_id']) {
			update_post_meta( $post->ID, 'kepler_composition_id',$request['kepler_composition_id']);
		}
	}

	// Appends composition (header/footer) assigned to each page for GET
	public function kepler_append_compositions_to_pages($data, $post, $request){

		$response_data = $data->get_data();

		if ( $request['context'] !== 'view' || is_wp_error( $data ) ) {
		    return $data;
		}
		$compId = get_post_meta( $post->ID, 'kepler_composition_id', true );
		
		if($compId == '') {
			$compId = get_site_option('kepler_default_composition');
		} 

		$composition = get_post($compId);

		$response_data['kp_composition'] = array(
			'title' => $composition->post_title,
			'id' =>$compId
		);

		$data->set_data( $response_data );

		return $data;
	}

	// Sets flag to determine default composition
	public function kepler_include_default_comp_in_compositions($data, $post, $request){

		$response_data = $data->get_data();

		if ( $request['context'] !== 'view' || is_wp_error( $data ) ) {
		    return $data;
		}
		
		$compId = get_site_option('kepler_default_composition');
		
		if(!$compId || $compId == '') {
			return $data;
		}
		
		$response_data['is_default_composition'] = (int)$compId == $post->ID;

		$data->set_data( $response_data );

		return $data;
	}
	
	/**
	 * SET envato key
	 * @return		json success callback
	 * @since			1.0.0
	*/
	public function kepler_register_product() {
		$code = $_POST['code'];
		$existingCode = $_POST['existingCode'];
		$url = 'https://keplerapis.com/validatekey?code=' . $code."&exisitingCode=".$existingCode;
		$request = wp_remote_get($url);
		$response = json_decode(wp_remote_retrieve_body( $request ));
		
		if($response->status != 'valid') {
			$error = array(
				'error' => 'Invalid Product Key'
			);
			if($response->status == 'existing'){
				$error = array(
					'error' => "Kepler does not allow switching to trial"
				);
			}
			wp_send_json_error($error);
		}

		update_option('envato_purchase_code_kepler', $code);

		$return = array(
			'message'=> 'Product registered successfully!'
		);

		wp_send_json_success($return); 

	}

	/**
	 * GET envato registration key
	 * @return		json
	 * @since			1.0.0
	*/
	public function kepler_get_registration() {
		$isRegistered = get_option('envato_purchase_code_kepler');
		
		if(!isset($isRegistered) || !$isRegistered) {
			$error = array(
				'error' => 'Product not registered'
			);
			wp_send_json_error($error);
		}

		$return = array(
			'message'=> 'Product already registered'
		);
		wp_send_json_success($return); 
	}


	/**
	 * SET diagnostic data flag for WP admin
	 * @return		json success callback
	 * @since			1.0.0
	*/
	public function kepler_enable_diagnostic() {
		$enable = $_POST['enable'];

		update_option('kepler_diagnostic', $enable);

		$return = array(
			'message'=> 'Diagnostic ' . $enable
		);

		wp_send_json_success($return); 

	}


	/**
	 * GET kepler status - check if wizard has run once
	 * @return		json success callback
	 * @since			1.0.0
	*/
	public function kepler_get_wizard_status() {

		$status = get_option('kepler_wizard_ran');

		$return = array(
			'message'=> $status
		);

		wp_send_json_success($return); 

	}

	/**
	 * SET kepler status once wizard finishes
	 * @return		json success callback
	 * @since			1.0.0
	*/
	public function kepler_set_wizard_status() {
		$id = $_POST['id'];
		update_option('kepler_wizard_ran', true);
		if($id){
			update_option('kepler_current_style_kit', $id);
		}
		$return = array(
			'message'=> 'Wizard ran'
		);

		wp_send_json_success($return); 

	}

	/**
	 * SET key as expired after save
	 * @return		json success callback
	 * @since			1.0.7
	*/
	public function kepler_set_trial_status() {
		$status = $_POST['status'];
		update_option('kepler_trial_expired', $status);
		$return = array(
			'message'=> 'Expired'
		);
		wp_send_json_success($return); 

	}

	/**
	 * SET key type after save
	 * @return		json success callback
	 * @since			1.0.7
	*/
	public function kepler_set_key_type() {
		$type = $_POST['type'];
		update_option('kepler_key_type', $type);
		$return = array(
			'message'=> 'Success'
		);
		wp_send_json_success($return); 

	}

}