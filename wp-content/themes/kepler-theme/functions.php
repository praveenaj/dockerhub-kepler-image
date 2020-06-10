<?php
/**
 * kepler_theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kepler_theme
 */

 // Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}

if ( ! function_exists( 'kepler_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */


include_once get_template_directory() . '/inc/class-kepler-theme.php';
include_once get_template_directory() . '/inc/class-kepler-theme-admin.php';

$kepler_themeAdminPages = array('kepler_theme', 'kepler_theme_install', 'kepler_theme_overview', 'kepler_theme_settings', 'kepler_theme_help');

/**
 * Instantiates the kepler_theme (singleton) class. 
 *
 * @return object kepler_theme
 */

function kepler_theme() {
	return kepler_theme_Layout::get_instance();
}

new kepler_theme_Admin();

function kepler_theme_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on kepler_theme, use a find and replace
	 * to change 'kepler_theme' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'kepler_theme', get_template_directory() . '/languages' );
	
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'main-menu' => esc_html__( 'Primary Menu', 'kepler_theme' ),
		'footer-social-menu' => esc_html__( 'Social Menu', 'kepler_theme' )
	));

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'kepler_theme_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
	) );

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	$GLOBALS['content_width'] = apply_filters( 'kepler_theme_content_width', 640 );
}
endif;
add_action( 'after_setup_theme', 'kepler_theme_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function kepler_theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'kepler_theme' ),
		'id'            => 'main-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'kepler_theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Mailchimp Widget', 'kepler_theme' ),
		'id'            => 'kepler_theme-mailchimp-widget-area',
		'description'   => esc_html__( 'Add widgets here.', 'kepler_theme' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="hidden">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'kepler_theme_widgets_init' );

/**
 * Set default image size in post editor to our own custom
 * image size and alignment to center
 */
function kepler_theme_custom_image_size() {
    update_option( 'image_default_align', 'center' );
	update_option( 'large_size_w', 1270 );
	update_option( 'large_size_h', 9999 );
	update_option( 'large_crop', 0 );
	update_option('image_default_size', 'large' );
}
add_action('after_setup_theme', 'kepler_theme_custom_image_size');

/**
 * Move comment field to bottom
 */
function kepler_theme_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}
add_filter( 'comment_form_fields', 'kepler_theme_move_comment_field_to_bottom' );

/**
 * Enqueue scripts and styles. 
 */
function kepler_theme_scripts() {
	// Read site options set by kepler plugin
	$current_css = get_site_option("kepler_style_kit_css");
	$fonts = json_decode(get_site_option("kepler_page_required_fonts"), true);

	if(is_array($fonts)){
		foreach ($fonts as $obj) {

				if($obj["type"] == "google"){
					$baseURL = "//fonts.googleapis.com/css?";
				}
				elseif($obj["type"] == "custom" || $obj["type"] == "kepler"){
					$baseURL = "//keplerapis.com/fonts/css/?";
				}
				else{
					continue;
				}
				$weights ="";
				$weights = ":".join(",",$obj["variants"]);
				$name = str_replace(' ','-',$obj["name"]);
				wp_enqueue_style( $obj["type"].'-fonts-'.$name, $baseURL.'family='.$obj["name"].''.$weights, array());
		}
	}
	/* START_CSS_LOADER */
	// if stylekit is set, load that CSS instead of theme's style.css
	if($current_css !=""){
		wp_enqueue_style( 'kepler_theme-reset', get_template_directory_uri() . '/reset.css' );
		wp_enqueue_style( 'kepler_theme', wp_upload_dir()["baseurl"].$current_css );
	}
	// load theme's style.css. This will be loaded on fresh install, when default stylekit is not modified 
	else{
		//default google fonts
		wp_enqueue_style( 'kepler_theme-google-fonts', 'https://fonts.googleapis.com/css?family=IBM+Plex+Mono&display=swap', false ); 
		wp_enqueue_style( 'kepler_theme', get_stylesheet_uri() );
	}
	/* END_CSS_LOADER */

	// Enqueue third party libraries 
	wp_enqueue_script( 'kepler_theme-lib-js-velocity', get_template_directory_uri() . '/js/lib/velocity.min.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'kepler_theme-lib-js-velocity-ui', get_template_directory_uri() . '/js/lib/velocity.ui.min.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'kepler_theme-lib-js-swiper', get_template_directory_uri() . '/js/lib/swiper.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'kepler_theme-lib-js-googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key='.get_site_option("kepler_google_key"), '', '' );
	wp_enqueue_script( 'kepler_theme-lib-js-clamp', get_template_directory_uri() . '/js/lib/clamp.min.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'kepler_theme-lib-js-menuclipper', get_template_directory_uri() . '/js/lib/jquery.menuclipper.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'kepler_theme-lib-js-color-thief', get_template_directory_uri() . '/js/lib/color-thief.min.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'kepler_theme-lib-js-vimeo', get_template_directory_uri() . '/js/lib/vimeo.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'kepler_theme-lib-js-youtube', get_template_directory_uri() . '/js/lib/youtube.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'kepler_theme-lib-js-fontfaceobserver', get_template_directory_uri() . '/js/lib/fontfaceobserver.js', array('jquery'), '1.0', true );
	
	// Enqueue core kepler_theme JS
	wp_enqueue_script( 'kepler_theme-core-js', get_template_directory_uri() . '/js/kepler_theme.js', array('jquery'), '1.0', true );

	// IE Support for SVG icons
	wp_enqueue_script( 'kepler_theme-ie-svg', get_template_directory_uri().'/js/lib/svgxuse.min.js', array(), '1.0', false);
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kepler_theme_scripts' );


/**
 * Enqueue Google Font used in Blog pages 
 */
function kepler_theme_google_fonts() 
{
	function is_blog () {
		return ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()) && 'post' == get_post_type();
	}
	if(is_blog()) {
		//You can load blog specific fonts here
	}
}
add_action( 'wp_enqueue_scripts', 'kepler_theme_google_fonts' );

/**
 * Load the comment walker.
 */
require_once get_template_directory() . '/inc/class-kepler-theme-comment-walker.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/*
* Remove this filter if you want to make comments box GDPR compliant
*/
function kepler_theme_comment_form_change_cookies_consent( $fields ) {
	$fields['cookies'] = '';
	return $fields;
}
add_filter( 'comment_form_default_fields', 'kepler_theme_comment_form_change_cookies_consent' );

/*
* Replaces double line-breaks with paragraph elements only for posts
*/
function kepler_theme_page_content_filter($content) {
	global $post;
	if($post AND $post->post_type === 'post'){
		$content = wpautop($content,false);
	}	 
	return $content; 
}
add_filter( 'the_content', 'kepler_theme_page_content_filter' );

/*
* Safely remove class if kp_ shortcodes are not present in page content
*/
function kepler_theme_safe_remove_body_class( $wp_classes ) {
	global $post;
	if($post){
		if(!has_shortcode( $post->post_content, 'kp_section' )){
			$blacklist = array('page');
			// Remove classes from array
			$wp_classes = array_diff( $wp_classes, $blacklist );
		}
	}	 
    // Return modified body class array
    return $wp_classes;
}
add_filter( 'body_class', 'kepler_theme_safe_remove_body_class', 10, 2 );

/*
* Append kepler_theme_admin class to wp admin body
*/
function kepler_theme_admin_body_class( $classes ) {
	global $pagenow;
    global $kepler_themeAdminPages;

	$bodyClass = "$classes";

	if($pagenow == 'admin.php' &&
		isset($_GET['page']) && 
		in_array($_GET['page'], $kepler_themeAdminPages)){
		$bodyClass .= " kepler_theme-plugin-page-styles";
	} 

	$wizardRan = get_option('kepler_wizard_ran');

    if($wizardRan != '1') {
		$bodyClass .= " kepler_wizard_not_run";
		
	}
	
	return $bodyClass;
     
}


add_filter( 'admin_body_class', 'kepler_theme_admin_body_class' );

/**
 * Appends a custom footer for all kepler_theme pages in WP Admin
 */
function kepler_theme_admin_custom_footer() {

	global $pagenow;
    global $kepler_themeAdminPages;

	if($pagenow == 'admin.php' && 
		isset($_GET['page']) && 
		in_array($_GET['page'], $kepler_themeAdminPages)){
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
add_action('admin_footer', 'kepler_theme_admin_custom_footer');

/**
 * Checks if a page is not kepler_theme default tab in WP Admin
 * @return bool
 */
function isNotOverview($page) {

	$isEssentialsInstalled = class_exists('Kepler_Builder');
	$redirectToPage = $isEssentialsInstalled ? 'kepler_theme_overview' : 'kepler_theme';

	return $page != $redirectToPage;
}

/**
 * Allow or disallow access to a kepler_theme tab in WP admin. 
 * If the pre-requisites are not met redirect to default tab
 */
function kepler_theme_disallowed_admin_pages() {
    global $pagenow;
    global $kepler_themeAdminPages;

	$isEssentialsInstalled = class_exists('Kepler_Builder');

	$redirectToPage = $isEssentialsInstalled ? 'kepler_theme_overview' : 'kepler_theme';

	$wizardNotRan = get_option('kepler_wizard_ran') != '1';

	$notOverview = array_filter($kepler_themeAdminPages, 'isNotOverview');


	if($pagenow == 'admin.php' && 
		isset($_GET['page']) && 
		in_array($_GET['page'], $notOverview) && 
		$wizardNotRan) {

        wp_redirect( admin_url( '/admin.php?page=' . $redirectToPage), 301 );
        exit;

    }

}

add_action( 'admin_init', 'kepler_theme_disallowed_admin_pages' );
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );
/* START_DEMO_LINES */

/* START_DEMO_LINES */