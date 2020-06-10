<?php


/**
 * Kepler Fonts
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 */

/**
 * Admin functions used by the builder for google and typekit fonts
 *
 * All functions are registered as wp_ajax in includes/class-kepler.php
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 * @author     Revox <support@revox.io>
 */
class Kepler_Font {

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
	 * SET type kit key
	 * 
	 * @return		JSON callback
	 * @since		1.0.0
	*/	
	public function kepler_set_typekit_kit() {
		$kit_id = $_POST['kit_id'];
		$option_name = "kepler_builder_user_typekitID";
		if ( get_option( $option_name ) !== false ) {
			update_option( $option_name, $kit_id );
		} else {
			$deprecated = null;
			$autoload = 'no';
			add_option( $option_name, $kit_id, $deprecated, $autoload );
		}
		$return = array(
			'message'=>$kit_id
		);
		wp_send_json_success($return);
  }
	
	/**
	 * GET type kit key
	 * 
	 * @return		JSON
	 * @since		1.0.0
	*/		
	public function kepler_get_adobe_typekit(){

		$typekit_ID=$_POST['kit_id'];
		$response = wp_remote_get( "https://typekit.com/api/v1/json/kits/".$typekit_ID."/published");
		if ( is_array( $response ) ) {
		  $header = $response['headers']; // array of http header lines
		  $body = $response['body']; // use the content
		}
		$familyKit=str_replace("slug","family",$body);
		$familyKit=str_replace("variations","variants",$familyKit);

		wp_send_json_success($familyKit);
	}
    
}