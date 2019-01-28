<?php

/**
 * WordPress plugin generator by Altimea
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.altimea.com
 * @since             1.0.0
 * @package           PaymentFitnes
 *
 * @wordpress-plugin
 * Plugin Name:       Paymentfitnes
 * Plugin URI:        http://www.altimea.com
 * Description:       Plugin developed by Altimea for their beloved client
 * Version:           1.0.0
 * Author:            Altimea
 * Author URI:        http://www.altimea.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       paymentfitnes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'PAYMENTFITNES_FILE' ) ) {
	define( 'PAYMENTFITNES_FILE', __FILE__ );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-paymentfitnes-activator.php
 * @param Boolean $networkwide status multisite
 * @return Void
 */
function activate_paymentfitnes($networkwide) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-paymentfitnes-activator.php';
	PaymentFitnesActivator::activate($networkwide);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-paymentfitnes-deactivator.php
 * @param Boolean $networkwide status multisite
 * @return Void
 */
function deactivate_paymentfitnes($networkwide) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-paymentfitnes-deactivator.php';
	PaymentFitnesDeactivator::deactivate($networkwide);
}

register_activation_hook( __FILE__, 'activate_paymentfitnes' );
register_deactivation_hook( __FILE__, 'deactivate_paymentfitnes' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/libraries/class-paymentfitnes-gulpfile.php';
// Add new classs
require plugin_dir_path( __FILE__ ) . 'includes/libraries/class-paymentfitnes-theme.php'; // 01. config theme
require plugin_dir_path( __FILE__ ) . 'includes/libraries/class-paymentfitnes-culqi.php'; // 02. config culqi

require plugin_dir_path( __FILE__ ) . 'includes/class-paymentfitnes.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_paymentfitnes() {

	$plugin = new PaymentFitnes();
	$plugin->run();

}
run_paymentfitnes();
