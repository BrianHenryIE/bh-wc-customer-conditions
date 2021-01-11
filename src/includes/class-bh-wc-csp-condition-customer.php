<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * frontend-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BH_WC_CSP_Condition_Customer
 * @subpackage BH_WC_CSP_Condition_Customer/includes
 */

namespace BH_WC_CSP_Condition_Customer\includes;

use BH_WC_CSP_Condition_Customer\BrianHenryIE\WPPB\WPPB_Loader_Interface;
use BH_WC_CSP_Condition_Customer\BrianHenryIE\WPPB\WPPB_Plugin_Abstract;
use BH_WC_CSP_Condition_Customer\Woocommerce_Conditional_Shipping_And_Payments\WC_CSP_Conditions;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * frontend-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    BH_WC_CSP_Condition_Customer
 * @subpackage BH_WC_CSP_Condition_Customer/includes
 * @author     Brian Henry <BrianHenryIE@gmail.com>
 */
class BH_WC_CSP_Condition_Customer extends WPPB_Plugin_Abstract {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the frontend-facing side of the site.
	 *
	 * @since    1.0.0
	 *
	 * @param WPPB_Loader_Interface $loader The WPPB class which adds the hooks and filters to WordPress.
	 */
	public function __construct( $loader ) {
		if ( defined( 'BH_WC_CSP_CONDITION_CUSTOMER_VERSION' ) ) {
			$version = BH_WC_CSP_CONDITION_CUSTOMER_VERSION;
		} else {
			$version = '1.0.0';
		}
		$plugin_name = 'bh-wc-csp-condition-customer';

		parent::__construct( $loader, $plugin_name, $version );

		$this->set_locale();
		$this->define_wcsp_hooks();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	protected function set_locale() {

		$plugin_i18n = new I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to WooCommerce Conditional Shipping and Payments.
	 *
	 * @since    1.0.0
	 */
	protected function define_wcsp_hooks() {

		$plugin_wc_csp_conditions = new WC_CSP_Conditions();

		$this->loader->add_filter( 'woocommerce_csp_conditions', $plugin_wc_csp_conditions, 'add_conditions' );

	}

}
