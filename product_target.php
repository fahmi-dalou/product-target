<?php

/**
 * The plugin bootstrap file
 *
 *
 * @link              https://wordpress.org/plugins/
 * @since             1.0.0
 * @package           Product_target
 *
 * @wordpress-plugin
 * Plugin Name:       Product target
 * Plugin URI:        https://wordpress.org/plugins/
 * Description:       This plugin to show product list
 * Version:           1.0.0
 * Author:            fahmi dalou
 * Author URI:        https://wordpress.org/plugins/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       product_target
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'PRODUCT_TARGET_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-product_target-activator.php
 */
function activate_product_target() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-product_target-activator.php';
	Product_target_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-product_target-deactivator.php
 */
function deactivate_product_target() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-product_target-deactivator.php';
	Product_target_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_product_target' );
register_deactivation_hook( __FILE__, 'deactivate_product_target' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-product_target.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_product_target() {

	$plugin = new Product_target();
	$plugin->run();

}
run_product_target();
