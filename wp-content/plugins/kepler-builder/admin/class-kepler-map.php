<?php


/**
 * Kepler Maps
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 */

/**
 * Admin functions used by the builder for google maps
 *
 * All functions are registered as wp_ajax in includes/class-kepler.php 
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 * @author     Revox <support@revox.io>
 */
class Kepler_MapsUtil {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct() {

    }

	/**
	 * SET Google map api key
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_set_google_map_api() {
		$key = $_POST['google_key'];
		update_site_option('kepler_google_key',$key);
		$return = array(
			'message'=>'Key Saved'
		);
		wp_send_json_success($return);
	}

	/**
	 * Add new map style
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_add_new_custom_map() {
		$json = $_POST['json'];
		$staticMapString = $_POST['staticMapString'];

		$new_post_author = wp_get_current_user();
		//Setup Arg;
		$new_post = array(
			'post_author' => $new_post_author_id,
			'post_content' => '[kp_map_placeholder features="center=-33.9,151.14999999999998&zoom=12&format=png&maptype=roadmap&style='.$staticMapString.'" preset="map map-standard"][/kp_map_placeholder]',
			'post_status' => 'publish',
			'post_title' => "Custom Style",
			'post_type' => $post->post_type,
			'comment_status' => $post->comment_status
		);

		//Add Element
		$new_post_id = wp_insert_post(wp_slash($new_post));

		//Add Category
		wp_set_post_terms(
			$new_post_id,
			array($terms[0]->term_id),
			"kp_element_category"
		);
		//Add Meta
		add_post_meta($new_post_id,
			'data',
			$kp_shortcode[0]
		);
		add_post_meta($new_post_id,
			'kp_element_theme',
			$kp_element_theme[0]
		);
		add_post_meta($new_post_id,
			'kp_element_allow_duplicate',
			'true'
		);
		add_post_meta($new_post_id,
			'kp_element_user_created',
			$kp_element_user_created
		);

		$return = array(
			'message'=>'Success',
			'element_id'=>$new_post_id
		);
		wp_send_json_success($return);
	}

}
?>