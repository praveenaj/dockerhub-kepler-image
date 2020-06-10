<?php


/**
 * Importing Demo content
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
 * Functions are defined for importing demo content to WP
 * All functions are registered as wp_ajax in includes/class-kepler.php 
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 * @author     Revox <support@revox.io>
 */
class Kepler_Demo {

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
	 * GET Demo content from kepler API
	 * 
	 * @return		JSON
	 * @since		1.0.0
	*/
	public function kepler_get_demo_content() {
		$sitesUrl = "https://keplerapis.com/sites";
		$sitesResponse = wp_remote_get( $sitesUrl );

		$sitesJson = json_decode( wp_remote_retrieve_body( $sitesResponse ), true );
		
		$styles = "https://keplerapis.com/sites/stylekits";
		$stylesResponse = wp_remote_get( $styles );

		$stylesJson = json_decode( wp_remote_retrieve_body( $stylesResponse ), true );

		$stylekits = "https://keplerapis.com/stylekits";
		$stylekitsResponse = wp_remote_get( $stylekits );

		$stylekitJson = json_decode( wp_remote_retrieve_body( $stylekitsResponse ), true );

		$layouts = "https://keplerapis.com/layouts";
		$layoutsResponse = wp_remote_get( $layouts );

		$layoutsJson = json_decode( wp_remote_retrieve_body( $layoutsResponse ), true );
		foreach ($sitesJson as $key => $val){
			$id = $sitesJson[$key]["ref"]["id"];
			$sitesJson[$key]["style"] = array();
			$sitesJson[$key]["selected_style"] = new stdClass();
			foreach ($stylesJson as $key2 => $val2){
				if($stylesJson[$key2]["siteId"] == $id){
					$stylesJson[$key2]["stylekit_details"] = new stdClass();
					foreach ($stylekitJson as $key3 => $val3){
						if($stylekitJson[$key3]["stylekitName"] == $stylesJson[$key2]["stylekit"]){
							$stylesJson[$key2]["stylekit_details"] = $stylekitJson[$key3];
						}
					}
					array_push($sitesJson[$key]["style"], $stylesJson[$key2]);
				}
			}
			$sitesJson[$key]["layout"] = new stdClass();
			foreach ($layoutsJson as $layoutKey => $layoutVal){
				if($layoutsJson[$layoutKey]["id"] == $sitesJson[$key]["layoutId"]){
					$sitesJson[$key]["layout"] = $layoutsJson[$layoutKey];
				}
			}
		}
		$content = array(
			'stylekits' => $stylekitJson,
			'demos' => $sitesJson,
		);
		wp_send_json_success($content);
	}
}
?>