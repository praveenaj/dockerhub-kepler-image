<?php

/**
 * The builder functionality of the plugin.
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/builder
 */

/**
 * The builder functionality of the plugin.
 *
 * Initialize Backbone powered builder on top of
 * wordpress page
 *
 * @package    Kepler
 * @subpackage Kepler/builder
 * @author     Revox <support@revox.io>
 */


class Kepler_Builder {

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
	private $settings;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'template_redirect', array( $this, 'init' ) );

	}

	/**
	 * Prepare builder interface by removing unnecessary components added by wordpress
	 * and enqueues the required scripts and styles for the builder
	 */
	public function init(){
		global $is_IE;

		function kepler_dequeue_user_style() {
		    wp_dequeue_style('kepler-user-style');
		    wp_deregister_style('kepler-user-style');

		}

		if(self::isPreviewMode()) {
			add_filter( 'show_admin_bar', '__return_false' );
			add_filter( 'body_class', function( $classes ) {
				return array_merge( $classes, array( 'page-designer' ) );
			});
			remove_action('wp_head', '_admin_bar_bump_cb');
			add_action( 'wp_enqueue_scripts', 'kepler_dequeue_user_style', 9999 );
			add_action( 'wp_head', 'kepler_dequeue_user_style', 9999 );

		}

		if(!self::isEditorMode()) return;

		$this->settings =  $this->setUpBuildViewOption();
		add_filter( 'template_include', array( $this, 'replaceTemplate' ) );
		add_filter( 'show_admin_bar', '__return_false' );
		remove_action('wp_head', '_admin_bar_bump_cb');

		// reset header
		remove_all_actions( 'wp_head' );
		remove_all_actions( 'wp_print_styles' );
		remove_all_actions( 'wp_print_head_scripts' );


		// // reset footer
		// remove_all_actions( 'wp_footer' );

		add_action( 'wp_head', 'wp_enqueue_scripts', 1 );
		add_action( 'wp_head', 'wp_print_styles', 2 );
		add_action( 'wp_head', 'wp_print_head_scripts', 3 );

		// remove all styles and scripts inserted by wordpress
		add_action('wp_print_styles', array($this, 'remove_all_styles'), 100);
		add_action('wp_print_scripts', array($this, 'remove_all_scripts'), 100);

		// Enqueue styles
		//wp_enqueue_style( 'fontawesome', 'http://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), $this->version, 'all' );
		wp_enqueue_style( 'medium-editor', plugin_dir_url( __FILE__ ) . 'assets/bower_components/medium-editor/css/medium-editor.min.css', array(), $this->version, 'all' );

		// wp_enqueue_style( 'dropzone', plugin_dir_url( __FILE__ ) . 'assets/bower_components/dropzone/min/dropzone.min.css', array(), $this->version, 'all' );
		// wp_enqueue_style( 'roboto', 'https://fonts.googleapis.com/css?family=Roboto:400,300,500,700', array(), $this->version, 'all' );
		$settings =  json_decode($this->settings[0]);
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/kepler-builder.css', array(), $this->version, 'all' );


		// enable enqueing less files
		// add_filter( 'style_loader_tag', array($this, 'enqueue_less_styles'), 5, 2);
		// wp_enqueue_style('less-css', plugin_dir_url( __FILE__ ) . 'assets/css/test.less', array(), $this->version, 'all');

		// Enqueue scripts
		wp_enqueue_script( 'requirejs', plugin_dir_url( __FILE__ ) . 'assets/js/lib/require.js', array('jquery' ), $this->version, true );
		// wp_enqueue_script( 'bootstrapjs', plugin_dir_url( __FILE__ ) . 'assets/bower_components/bootstrap/dist/js/bootstrap.js', array('jquery' ), $this->version, true );

		/* 
			Kepler depends on Backbone 1.3.3 and doesn't work with 
			default backbone version that comes with Wordpress 5.3 onwards
			https://make.wordpress.org/core/2019/10/10/wordpress-5-3-backbone-upgrade-guide/ 
		*/
		wp_deregister_script('backbone');
		wp_register_script('backbone', plugin_dir_url( __FILE__ ) . "assets/js/lib/backbone.js", array('underscore' ), null);
		wp_enqueue_script('backbone');

		wp_enqueue_script( 'wpapi', includes_url().'js/wp-api.js', array( 'wp-api' ) );
		wp_enqueue_script( 'pace', plugin_dir_url( __FILE__ ) . 'assets/js/lib/pace.min.js', array() );
		wp_enqueue_script( 'swiper', plugin_dir_url( __FILE__ ) . 'assets/js/lib/swiper.min.js', array() );

		if ( $is_IE ) { 
			include plugin_dir_path( dirname( __FILE__ ) ) . 'builder/partials/ie.php';
			return;
		}


// add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );

		$app_base = plugin_dir_url( __FILE__ ) . 'assets';
		$currentPostID = get_the_ID();
		$theme = wp_get_theme();
		
		// Read theme vars and pass them down to WP options JS variable
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/less.php/Autoloader.php';
		// Less_Autoloader::register();

		$stylekits = get_posts( array(
			'post_type' => 'kp_stylekit',
			'post_status' => array('publish')  
		));		
		
		if(!$stylekits){
			$id = (int)get_site_option('kepler_current_style_kit');
			$stylekit = get_post($id);
		}
		else{
			$stylekit = $stylekits[0];
		}

		$stylekitID = $stylekit->ID;
		//Fonts and LESS Vars
		$stylekitVar = get_post_meta($stylekitID,"var",true);
		if($stylekitVar && gettype($stylekitVar) === 'string'){
			$stylekitVar = json_decode($stylekitVar);
		}
				
		$kepler_local_styles = get_site_option("kepler_local_styles");
		if($kepler_local_styles && gettype($kepler_local_styles) === 'string'){
			$kepler_local_styles = stripslashes($kepler_local_styles);
		}

		$lessFile = get_post_meta($stylekitID,"less",true);
		if(!$lessFile){
			$lessFile = get_stylesheet_directory() .'/less/theme.less';
		}else{
			$destination = wp_upload_dir();
			$destination_path = $destination['basedir'];
			$lessFile = $destination_path."/kepler-styles/".$lessFile;
		}

		$googlekey = get_site_option("kepler_google_key");
		if($googlekey){
			$mapsParameters = array(
				'params' => array(
					'key'=>  $googlekey,
					'libraries'=>  'places'
				)
				);
		}
		else{
			$mapsParameters =  array(
				'params' => array(
					'libraries'=>  'places'
				)
				);
		}

		$isKeplerActive = get_option('envato_purchase_code_kepler') !== false;
		$isStylekitSet = get_option('kepler_current_style_kit') !== false;

		wp_localize_script( 'requirejs', 'require', array(
		    'baseUrl' => $app_base,
			'googlemaps' => $mapsParameters,
			'waitSeconds' => 0,
			'deps'    => array( $app_base . '/js/main.bundle.js'),

			'config' => array(
				
			    'helper/globals' => array(
					'WP_KEPLER_API' => "https://keplerapis.com/",
					'WP_SITE_URL' => get_site_url(),
			    	//'WP_AJAX_URL' => 'http://localhost:3400/wp-deploy/admin-ajax.php',
			    	'WP_AJAX_URL' => admin_url( 'admin-ajax.php' ),
			    	'WP_PLUGIN_DIR' => plugin_dir_url(__FILE__),
					'WP_PAGE_ID' => $currentPostID,
					'WP_HOME_URL' => home_url(),
			    	'WP_PAGE_URL' => get_permalink($currentPostID),
					'WP_PAGE_NAME' =>get_the_title($currentPostID),
			    	'WP_REST_URL' => get_rest_url(),
			    	'WP_THEME_URL' => get_template_directory_uri(),
			    	'WP_THEME_NAME' => $theme->get( 'Name' ),
			    	'WP_SITE_NAME' => get_bloginfo( 'name' ),
					'WP_GOOGLE_API_KEY' => get_site_option("kepler_google_key"),
					'WP_KEPLER_KEY' => get_site_option("envato_purchase_code_kepler"),
			    	'WP_BUILDER_OPTIONS' => $this->settings,
			    	'WP_FONTS'=> get_site_option("kepler_builder_user_fonts"),
					'WP_TYPEKIT'=> get_site_option("kepler_builder_user_typekitID"),
					'WP_ICON_STYLE' => get_site_option("kepler_style_kit_icons"),
					'WP_PUBLISHED_STYELKIT' => get_site_option('kepler_current_style_kit'),
					'WP_BUILDER_VERSION' => '1.0.9',
					'WP_STYELKIT' => array(
						'ID'=>$stylekitID,
						'NAME'=>get_post_meta($stylekitID,"kit_name",true),
						'VERSION'=>get_post_meta($stylekitID,"version",true),
						'THUMB' => get_post_meta($stylekitID,"thumbnail_url",true),
						'CSS' => get_post_meta($stylekitID,"css_file",true),
						'JS' => get_post_meta($stylekitID,"js_file",true),
						'ICONS_TYPE' => get_post_meta($stylekitID,"icon_type",true),
						'STYLES' => $stylekit->post_content,
						'LOCAL_STYLES'=>$kepler_local_styles,
						'VAR' => $stylekitVar,
						'DATE' => $stylekit->post_date
					),
					'WP_IS_BUILDER_READY' => $isKeplerActive && $isStylekitSet,
					//'WP_IS_BUILDER_READY' => true,
					'WP_CACHE' => array(),
					'WP_IS_PLAN_MODE' => Kepler_Builder::isPlanMode(),
					'WP_ALLOW_ERROR_LOG' => get_option('kepler_diagnostic', "true"),
					'WP_PHP_CONFIG' => array(
						'max_input_time' => ini_get('max_input_time'),
						'max_execution_time' => ini_get('max_execution_time')
					),
					'WP_TRIAL_EXPIRED'=>get_option('kepler_trial_expired'),
					'WP_KEY_TYPE'=>get_option('kepler_key_type'),
				),
			   )
		));

	}


	private function setUpBuildViewOption(){
		$user_id = get_current_user_id();
		$meta = get_user_meta( $user_id, "kepler_builder_settings");
		$builderOptions = new stdClass();
		if($meta){
			return $meta;
		}
		else{
			$builderOptions = $this->getDefaultSettings();
			$this->registerUserMeta($builderOptions);
		}

		return json_encode($builderOptions);
	}
	private function getDefaultSettings(){
		$builderOptions = new stdClass();
		$builderOptions->ScreenMode = 0;
		$builderOptions->InterFaceColor = 0;
		$builderOptions->Grids = 1;
		$builderOptions->Guides = 1;
		$builderOptions->LayerHoverHighlights = 1;
		$builderOptions->LayerEdges = 1;
		$builderOptions->CanvasWidth = 0;
		return $builderOptions;
	}
	private function validateSettings($obj){

		foreach($obj as $item) { //foreach element in $arr
		    echo $item;
		}
	}
	private function registerUserMeta($arg){
		$user_id = get_current_user_id();
		add_user_meta( $user_id, "kepler_builder_settings", wp_strip_all_tags(json_encode($arg)));
	}

 	public function remove_all_styles() {
	    global $wp_styles;

	    $styles_to_keep = array("kepler", "fontawesome", "material-icons", $this->plugin_name."_icons");
		// loop over all of the registered scripts
		foreach ($wp_styles->registered as $handle => $data)
		{
			if ( in_array($handle, $styles_to_keep) ) continue;
			// otherwise remove it
			wp_deregister_style($handle);
			wp_dequeue_style($handle);
		}
	}

 	public function remove_all_scripts() {
	    global $wp_scripts;
	    // $wp_styles->queue = array();
	    $scripts_to_keep = array("kepler", "requirejs", "wpapi", 'pace', 'swiper');

		// loop over all of the registered scripts
		foreach ($wp_scripts->registered as $handle => $data)
		{
			// if we want to keep it, skip it
			if ( in_array($handle, $scripts_to_keep) ) continue;

			// otherwise remove it
			wp_dequeue_script($handle);
		}
	}

	/**
	 * Override template render
	 */

	public function replaceTemplate(){
		$settings =  json_decode($this->settings[0]);
		$classes = "";
		switch ($settings->ScreenMode)
	    {
	        case 1:
	        $classes = $classes." standard-mode";
	        break;
	        case 1:
	        $classes = $classes." space-mode";
	        break;
	        case 2:
	        $classes = $classes." preview-mode";
	        break;
	    }
	    switch ($settings->InterFaceColor)
	    {
	        case 0:
	        $classes = $classes." dark";
	        break;
	        case 1:
	        $classes = $classes." light";
	        break;
	    }
	    switch ($settings->CanvasWidth)
	    {
	        case 1:
	        $classes = $classes." canvas-full-width";
	        break;
		}
		
		if(self::isPlanMode()) {
			$classes .= ' tab-plan-active';
		}
	

		echo "<html><head>";
		wp_head();
		// Echoing since we can't enqueue less with rel="stylesheet/less" attr
		// echo '<link id="kepler_less" rel="stylesheet/less" type="text/css" href="'.get_template_directory_uri().'/less/theme.less" />';
		// echo '<link id="kepler_preview_less" rel="stylesheet/less" type="text/css" href="'.plugin_dir_url( __FILE__ ) . 'assets/less/preview.less" />';
		echo "</head><body class='".$classes."'>";

		include plugin_dir_path( dirname( __FILE__ ) ) . 'builder/partials/kepler-builder.php';

		wp_footer();
		echo "</body></html>";

		return false;
	}

	/**
	 * Checks if the URL has 'kepler_builder' parameter set
	 */
	public static function isEditorMode(){
		return isset($_GET['kepler_builder']) && $_GET['kepler_builder'] == 1;
	}

	/**
	 * Checks if the URL has 'kepler_builder' parameter set
	 */
	public static function isPreviewMode(){
		return isset($_GET['kepler_builder_preview']) && $_GET['kepler_builder_preview'] == 1;
	}

	public static function isPlanMode() {
		return isset($_GET['kepler_builder_plan']);
	}

	public function getFooterPermalink(){
		$footerPostId = get_site_option('kepler_footer_reference');
		if($footerPostId == "")
			return get_post_type_archive_link('kp_footer_blocks');
		return get_permalink( $footerPostId );
	}



}
