<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           BrianHenryIE\WC_Customer_Conditions
 *
 * @wordpress-plugin
 * Plugin Name:       Customer Conditions
 * Plugin URI:        http://github.com/BrianHenryIE/bh-wc-customer-condition/
 * Description:       Use customer is-paying-customer, order count, and total spend as restrictions in coupons and in WooCommerce Conditional Shipping and Payments.
 * Version:           1.3.1
 * Author:            Brian Henry
 * Author URI:        http://BrianHenryIE.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bh-wc-customer-condition
 * Domain Path:       /languages
 */

namespace BrianHenryIE\WC_Customer_Conditions;

use BrianHenryIE\WC_Customer_Conditions\Includes\BH_WC_Customer_Conditions;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'autoload.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BH_WC_CUSTOMER_CONDITIONS_VERSION', '1.3.1' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function instantiate_bh_wc_customer_conditions():void {

	new BH_WC_Customer_Conditions();

}

instantiate_bh_wc_customer_conditions();
