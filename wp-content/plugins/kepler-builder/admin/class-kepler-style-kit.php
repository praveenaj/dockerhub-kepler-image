<?php


/**
 * Kepler Stylekits
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 */

/**
 * Admin functions used by the builder for kepler stylekit import and saving
 *
 * All functions are registered as wp_ajax in includes/class-kepler.php 
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 * @author     Revox <support@revox.io>
 */
class Kepler_Stylekit
{

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct()
	{ }

	/**
	 * Switch stylekit
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	 */
	public function kepler_switch_stylekit()
	{
		if ($_POST['new_id'] == "") {
			wp_send_json_error(array(
				'error' => 'Missing Stylekit ID'
			));
			return;
		}
		if ($_POST['old_id'] != "") {
			$old_kit = get_post($_POST['old_id']);
			wp_transition_post_status("draft", "publish", $old_kit);
		}
		$new_kit = get_post($_POST['new_id']);
		wp_transition_post_status("publish", "draft", $new_kit);

		$return = array(
			'data' => $new_kit
		);
		wp_send_json_success($return);
	}

	/**
	 * Delete stylekit
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	 */
	public function kepler_remove_style_kit()
	{
		if ($_POST['id'] == "" || $_POST['name'] == "") {
			wp_send_json_error(array(
				'error' => 'Missing Stylekit Data'
			));
			return;
		}
		$name = $_POST['name'];
		$id = $_POST['id'];
		wp_delete_post($id);
		wp_send_json_success(array(
			'message' => "Data Saved"
		));
	}

	/**
	 * Reset Stylekit to default
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	 */
	public function kepler_restore_default_stylekit()
	{
		if ($_POST['id'] == "" || $_POST['varFile'] == "" || $_POST['jsonFile'] == "") {
			wp_send_json_error(array(
				'error' => 'Missing Stylekit Data'
			));
			return;
		}
		$id = $_POST['id'];
		$jsonFile = $_POST['jsonFile'];
		$varFile = $_POST['varFile'];
		$access_type = get_filesystem_method();
		if ($access_type === 'direct') {
			/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
			$creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());

			/* initialize the API */
			if (!WP_Filesystem($creds)) {
				wp_send_json_error("No Access to wp-uploads");
				return;
			}
			$destination = wp_upload_dir();
			$destination_path = $destination['basedir'];

			$jsonFile = $destination_path . '/kepler-styles' . '/' . $jsonFile;
			$varFile = $destination_path . '/kepler-styles' . '/' . $varFile;

			if (file_exists($jsonFile) && file_exists($varFile)) {
				$styleJSON = file_get_contents($jsonFile);
				$varJSON = file_get_contents($varFile);
				$varJSON = json_decode($varJSON, true);
				$return = array(
					'var' => $varJSON,
					'styles' => $styleJSON
				);
			} else {
				wp_send_json_error(array(
					'error' => 'Missing Stylekit JSON file'
				));
				return;
			}
		} else {
			wp_send_json_error(array(
				'error' => 'No Access'
			));
			return;
		}

		wp_send_json_success($return);
	}

	/**
	 * Download Stylekit from Kepler API
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	 */
	public function kepler_download_style_kit()
	{
		$callback = $_POST["callback"];
		$key = get_option('envato_purchase_code_kepler');

		if (!isset($key) || $key === '') {
			wp_send_json_error(array(
				'error' => 'Product not registered!'
			));
		}
		$stylekit =  $_POST['stylekit'];

		$isSkeleton = isset($stylekit['skeleton']);
		if ($stylekit == "") {
			wp_send_json_error("No kit");
			return;
		}
		$filename = $stylekit["package"];
		$url = "https://us-central1-kepler-183318.cloudfunctions.net/download-stylekit";
		$response = wp_remote_post(
			$url,
			array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array('purchase_key' => $key, 'id' => $stylekit["stylekitName"]),
				'cookies' => array()
			)
		);

		$styleJSON = '';

		if (is_array($response)) {

			$zip = $response['body'];
			$access_type = get_filesystem_method();
			if ($access_type === 'direct') {
				/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
				$creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());

				/* initialize the API */
				if (!WP_Filesystem($creds)) {
					wp_send_json_error();
					return;
				}
				$destination = wp_upload_dir();
				$destination_path = $destination['basedir'];

				$fp = fopen($destination_path . '/' . $filename, "w");
				fwrite($fp, $zip);
				fclose($fp);

				$unzipfile = unzip_file($destination_path . '/' . $filename, $destination_path);
				if (is_wp_error($unzipfile)) {
					wp_send_json_error("unzipProblem");
					return;
				}
				unlink($destination_path . '/' . $filename);

				$jsonFile = $destination_path . '/kepler-styles' . '/' . $stylekit["jsonfileName"];
				$varFile = $destination_path . '/kepler-styles' . '/' . $stylekit["varfileName"];

				if (file_exists($jsonFile) && file_exists($varFile)) {
					$styleJSON = file_get_contents($jsonFile);
					$varJSON = file_get_contents($varFile);
					$varJSON = json_decode($varJSON, true);
					if ($isSkeleton) {
						$stylekit["stylekitName"] = $stylekit['skeletonName'];
					}
					//chanhe name here
					$newid = $this->save_meta_data($stylekit, $styleJSON, $varJSON);
					if ($newid) {
						if ($callback) {
							$kit   = get_post($newid);
							$return = array(
								'stylekit' => $kit->post_content,
								'stylekit_id' => $kit->ID,
								"status" => $kit->post_status,
								"version" => get_post_meta($kit->ID, 'version', true),
								"thumbnail_url" => get_post_meta($kit->ID, 'thumbnail_url', true),
								"builder_version" => get_post_meta($kit->ID, 'builder_version', true),
								"style_kit_name" => get_post_meta($kit->ID, 'style_kit_name', true),
								"css_file" => get_post_meta($kit->ID, 'css_file', true),
								"js_file" => get_post_meta($kit->ID, 'js_file', true),
								"icon_type" => get_post_meta($kit->ID, 'icon_type', true),
								"varfileName" => get_post_meta($kit->ID, 'varfileName', true),
								"jsonfileName" => get_post_meta($kit->ID, 'jsonfileName', true),
								"var" => get_post_meta($kit->ID, "var")
							);
						} else {
							$return = array(
								'message' => "Data Saved"
							);
						}
					}
				} else {
					$return = array(
						'message' => "File Downloaded Only"
					);
				}
			} else {
				wp_send_json_error();
				return;
			}
		} else {
			$return = array(
				'message' => "Not working"
			);
		}

		wp_send_json_success($return);
	}
	/**
	 * Download Stylekit from Kepler API
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	 */

	public function kepler_update_stylekit()
	{

		if ($_POST['style_kit_name'] == "") {
			wp_send_json_error();
			return;
		}


		$stylekitName = $_POST['style_kit_name'];
		$stylekit = get_page_by_title('kepler_stylekit_' . $stylekitName, OBJECT, 'kp_stylekit');
		$stylekit_postID = $stylekit->ID;
		$url = "https://us-central1-kepler-183318.cloudfunctions.net/update-stylekit";
		$response = wp_remote_post(
			$url,
			array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array('old_stylekit' => $stylekit->post_content, 'kitName' => $stylekitName),
				'cookies' => array()
			)
		);

		if (is_array($response)) {
			// var_dump( json_decode( $response['body']));
			$my_post = array(
				'ID'           => $stylekit_postID,
				'post_content' => trim(json_encode(json_decode( $response['body'])),'"'),
			);
		  // Update the post into the database 
			$updatePost= wp_update_post( $my_post );
			update_post_meta(
				$stylekit_postID,
				'version',
				$_POST["version"]
			);
			update_post_meta(
				$stylekit_postID,
				'builder_version',
				$_POST["builderVersion"]
			);
			$results=array(
				'stylekit' => trim(stripslashes($response['body']),'"'),
				'version' => $_POST["version"],
			);

			if ($_POST['callback']) {
				wp_send_json_success($results);
			}
		}
	}
	/**
	 * Get All stylekit custom posts
	 *
	 * @return		JSON
	 * @since		1.0.0
	 */
	public function kepler_get_stylekits()
	{

		$stylekits = get_posts(array(
			'post_type' => 'kp_stylekit',
			'post_status' => array('publish', 'draft'),
			'posts_per_page' => -1,
			'numberposts' => -1
		));
		$newKits = array();
		foreach ($stylekits as $kit) {
			$stylekitVar = get_post_meta($stylekitID, "var");
			$varkit = array(
				"id"  => $kit->ID,
				"status" => $kit->post_status,
				"version" => get_post_meta($kit->ID, 'version', true),
				"thumbnail_url" => get_post_meta($kit->ID, 'thumbnail_url', true),
				"builder_version" => get_post_meta($kit->ID, 'builder_version', true),
				"style_kit_name" => get_post_meta($kit->ID, 'style_kit_name', true),
				"css_file" => get_post_meta($kit->ID, 'css_file', true),
				"js_file" => get_post_meta($kit->ID, 'js_file', true),
				"icon_type" => get_post_meta($kit->ID, 'icon_type', true),
				"varfileName" => get_post_meta($kit->ID, 'varfileName', true),
				"jsonfileName" => get_post_meta($kit->ID, 'jsonfileName', true),
			);
			array_push($newKits, $varkit);
		}
		wp_send_json_success($newKits);
	}

	/**
	 * Get css string from stylekit custom posts
	 *
	 * @return		JSON
	 * @since		1.0.0
	 */
	public function kepler_get_stylekit_styles()
	{
		if ($_POST['style_kit_id'] == "") {
			wp_send_json_error();
			return;
		}
		$stylekit = get_post($_POST['style_kit_id']);
		if (!$stylekit) {
			wp_send_json_error();
			return;
		}

		$stylekitVar = get_post_meta($stylekit->ID, "var", true);
		$stylekitName = get_post_meta($stylekit->ID, "kit_name", true);
		$icon_type = get_post_meta($stylekit->ID, "icon_type", true);

		$stylekit->stylekitVar = $stylekitVar;
		$stylekit->stylekitName = $stylekitName;
		$stylekit->icon_type = $icon_type;
		wp_send_json_success($stylekit);
	}

	/**
	 * Save meta data to stylekit custom post
	 * @param		$stylekit - css string, $styleJSON - css JSON object, $varJSON - varriable object
	 * @access		private
	 * @since		1.0.0
	 */
	private function save_meta_data($stylekit, $styleJSON, $varJSON)
	{
		$currentStylekitId = post_exists("kepler_stylekit_" . $stylekit["stylekitName"]);
		if ($currentStylekitId != 0) {
			//Update
			$stylekit_arg = array(
				'ID'           => $currentStylekitId,
				'post_content' => $styleJSON
			);
			wp_update_post($stylekit_arg);
			update_post_meta(
				$currentStylekitId,
				'version',
				$stylekit["version"]
			);
			update_post_meta(
				$currentStylekitId,
				'thumbnail_url',
				$stylekit["thumbnail"]
			);
			update_post_meta(
				$currentStylekitId,
				'builder_version',
				$stylekit["builderVersion"]
			);
			return $currentStylekitId;
		} else {
			//Add New
			$new_stylekit_arg = array(
				'post_title' => "kepler_stylekit_" . $stylekit["stylekitName"],
				'post_type' => 'kp_stylekit',
				'post_status' => 'draft',
				'post_content' => $styleJSON
			);

			$new_style_id = wp_insert_post(wp_slash($new_stylekit_arg));
			add_post_meta(
				$new_style_id,
				'kit_name',
				$stylekit["stylekitName"]
			);
			add_post_meta(
				$new_style_id,
				'version',
				$stylekit["version"]
			);
			add_post_meta(
				$new_style_id,
				'thumbnail_url',
				$stylekit["thumbnail"]
			);
			add_post_meta(
				$new_style_id,
				'var',
				json_encode($varJSON)
			);
			add_post_meta(
				$new_style_id,
				'builder_version',
				$stylekit["builderVersion"]
			);
			add_post_meta(
				$new_style_id,
				'style_kit_name',
				$stylekit["stylekitName"]
			);
			add_post_meta(
				$new_style_id,
				'jsonfileName',
				$stylekit["jsonfileName"]
			);
			add_post_meta(
				$new_style_id,
				'varfileName',
				$stylekit["varfileName"]
			);
			add_post_meta(
				$new_style_id,
				'css_file',
				$destination["baseurl"] . "/kepler-styles/" . $stylekit["cssfileName"]
			);
			add_post_meta(
				$new_style_id,
				'js_file',
				$destination["baseurl"] . "/kepler-scripts/" . $stylekit["jsfileName"]
			);
			add_post_meta(
				$new_style_id,
				'icon_type',
				$stylekit["iconfileName"]
			);
			add_post_meta(
				$new_style_id,
				'obj',
				$destination["baseurl"] . "/kepler-styles/" . $stylekit["objfileName"]
			);

			return $new_style_id;
		}
	}
}
