<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Kepler
 * @subpackage Kepler/includes
 * @author     Revox <support@revox.io>
 */
class Kepler_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Remove Old ones
		$allposts= get_posts( array('post_type'=>'kp_elements','numberposts'=>-1) );
		foreach ($allposts as $eachpost) {
			wp_delete_post( $eachpost->ID, true );
		}
	}
}
