<?php


/**
 * Kepler Builder Elements
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 */

/**
 * Admin functions defined for kepler builder elements.
 * All functions are registered as wp_ajax in includes/class-kepler.php 
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 * @author     Revox <support@revox.io>
 */
class Kepler_Elements {

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
	 * Duplicate element
	 * 
	 * @return		JSON
	 * @since		1.0.0
	*/
	public function kepler_duplicate_element(){
		$id = intval( $_POST['element_id'] );
		$post = get_post($id);
		$stylekitId = intval( $_POST['stylekitId'] );
		$previewShortCode = $_POST['previewShortCode'];
		$kp_shortCodeData = $_POST['elementShortCode'];

		$terms = get_the_terms( $post,"kp_element_category" );
		$kp_shortcode = stripslashes($previewShortCode);
		$kp_element_allow_duplicate = get_post_meta("kp_element_allow_duplicate",$id,true);
		$kp_element_user_created = true;

		if($post->post_type != "kp_elements"){
			$return = array(
				'message'=>"Nice Try - Illegal Attempt",
			);
			wp_send_json_error($return);
		}
		$new_post_author = wp_get_current_user();
		//Setup Arg;
		$new_post = array(
			'post_author' => $new_post_author_id,
			'post_content' => $kp_shortcode,
			'post_status' => $post->post_status,
			'post_title' => $post->post_title." copy",
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
			'kp_shortcode',
			$kp_shortcode
		);
		add_post_meta($new_post_id,
			'kp_data',
			$kp_shortCodeData
		);		
		add_post_meta($new_post_id,
			'kp_element_theme',
			$stylekitId
		);
		add_post_meta($new_post_id,
			'kp_element_allow_duplicate',
			$kp_element_allow_duplicate[0]
		);
		add_post_meta($new_post_id,
			'kp_element_user_created',
			$kp_element_user_created
		);

		add_post_meta($new_post_id,
			'kp_element_theme',
			$stylekitId
		);

		$return = array(
			'message'=>'Success',
			'element_id'=>$new_post_id
		);
		wp_send_json_success($return);
	}

	/**
	 * Remove Element
	 * 
	 * @return		JSON
	 * @since		1.0.0
	*/
	public function kepler_remove_element(){
		$id = intval( $_POST['element_id'] );
		$post = get_post($id);

		//TODO : USE CONSTANTS FOR STRINGS
		$terms = get_the_terms( $post,"kp_element_category" );
		$meta = get_post_custom_values("kp_element_type",$id);

		if($post->post_type != "kp_elements"){
			$return = array(
				'message'=>$post->ID,
			);
			wp_send_json_error($return);
		}
		//Remove Post Meta
		delete_post_meta($post->ID,
			'kp_element_type'
		);
		//Remove Term
		wp_remove_object_terms($post->ID,
			array($terms[0]->term_id),
			"kp_element_category"
		);
		//Remove Element
		wp_delete_post( $post->ID);

		$return = array(
			'message'=>'Success',
			'element_id'=>$post->ID
		);
		wp_send_json_success($return);
	}
}
?>