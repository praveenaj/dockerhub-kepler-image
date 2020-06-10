<?php
/**
 * Plugin Updater code using WP upadte API
 * 
 * For more information, see the following:
 * https://github.com/jeremyclark13/automatic-theme-plugin-update/blob/master/plugin/test-plugin-update/test-plugin-update.php
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 */

// Take over the update check
add_filter('pre_set_site_transient_update_plugins', 'kepler_check_for_plugin_update');

function kepler_check_for_plugin_update($checked_data) {
	global $wp_version;
    $api_url = 'https://update.keplerapis.com/';
    $plugin_slug = basename(dirname(__FILE__));
	//Comment out these two lines during testing.
	if (empty($checked_data->checked))
		return $checked_data;
	
	$args = array(
		'slug' => $plugin_slug,
		'version' => $checked_data->checked[$plugin_slug .'/kepler.php'],
	);
	$request_string = array(
			'body' => array(
				'action' => 'basic_check', 
				'request' => serialize($args),
                'api-key' => md5(get_bloginfo('url')),
                'envato-key' =>get_option('envato_purchase_code_kepler'),
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);
	
	// Start checking for an update
	$raw_response = wp_remote_post($api_url, $request_string);
    
	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200)){
        $response = unserialize($raw_response['body']);
    }
	
	if (is_object($response) && !empty($response)) // Feed the update data into WP updater
		$checked_data->response[$plugin_slug .'/kepler.php'] = $response;
	
	return $checked_data;
}


// Take over the Plugin info screen
add_filter('plugins_api', 'kepler_api_call', 10, 3);

function kepler_api_call($def, $action, $args) {
	global $wp_version;
    $api_url = 'https://update.keplerapis.com/';
    $plugin_slug = basename(dirname(__FILE__));
	if (!isset($args->slug) || ($args->slug != $plugin_slug))
		return false;
	
	// Get the current version
	$plugin_info = get_site_transient('update_plugins');
	$current_version = $plugin_info->checked[$plugin_slug .'/kepler.php'];
	$args->version = $current_version;
	
	$request_string = array(
			'body' => array(
				'action' => $action, 
				'request' => serialize($args),
				'api-key' => md5(get_bloginfo('url')),
				'envato-key' =>get_option('envato_purchase_code_kepler'),
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);
	
	$request = wp_remote_post($api_url, $request_string);
	
	if (is_wp_error($request)) {
		$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);
		
		if ($res === false)
			$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
	}
	
	return $res;
}
?>