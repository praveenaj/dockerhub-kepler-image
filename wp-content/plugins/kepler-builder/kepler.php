<?php

/**
 * Kepler Builder
 *
 * @link              http://revox.io
 * @since             1.0.0
 * @package           Kepler
 *
 * @wordpress-plugin
 * Plugin Name:       Kepler Builder
 * Plugin URI:        http://kepler.app
 * Description:       A revolutionary, powerful WordPress website builder that gives you endless design possibilities.
 * Version:           1.0.9
 * Author:            Revox
 * Author URI:        http://revox.io
 * Text Domain:       kepler
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kepler-activator.php
 */
function activate_kepler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kepler-activator.php';
	new Kepler_Activator();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kepler-deactivator.php
 */
function deactivate_kepler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kepler-deactivator.php';
	Kepler_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kepler' );
register_deactivation_hook( __FILE__, 'deactivate_kepler' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kepler.php';
// Plugin Updater
require plugin_dir_path( __FILE__ ) . 'update.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kepler() {

	$plugin = new Kepler();
	$plugin->run();
	
}
run_kepler();
