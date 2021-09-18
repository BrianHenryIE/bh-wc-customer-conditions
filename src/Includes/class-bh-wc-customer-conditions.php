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
 * @package    BrianHenryIE\WC_Customer_Conditions
 * @subpackage BrianHenryIE\WC_Customer_Conditions/includes
 */

namespace BrianHenryIE\WC_Customer_Conditions\Includes;

use BrianHenryIE\WC_Customer_Conditions\WooCommerce\Checkout;
use BrianHenryIE\WC_Customer_Conditions\WooCommerce\Coupons;
use BrianHenryIE\WC_Customer_Conditions\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Conditions;

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
 * @package    BrianHenryIE\WC_Customer_Conditions
 * @subpackage BrianHenryIE\WC_Customer_Conditions/includes
 * @author     Brian Henry <BrianHenryIE@gmail.com>
 */
class BH_WC_Customer_Conditions {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the frontend-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->set_locale();

		$this->define_wcsp_hooks();
		$this->define_checkout_hooks();
		$this->define_coupon_hooks();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	protected function set_locale(): void {

		$plugin_i18n = new I18n();

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

	}

	/**
	 * Register all of the hooks related to WooCommerce Conditional Shipping and Payments.
	 *
	 * @since    1.0.0
	 */
	protected function define_wcsp_hooks(): void {

		$plugin_wc_csp_conditions = new WC_CSP_Conditions();

		add_filter( 'woocommerce_csp_conditions', array( $plugin_wc_csp_conditions, 'add_conditions' ) );

	}


	protected function define_checkout_hooks(): void {

		$checkout = new Checkout();

		add_filter( 'woocommerce_billing_fields', array( $checkout, 'add_update_totals_class_to_billing_email' ), 10, 2 );
	}

	protected function define_coupon_hooks(): void {
		$coupons = new Coupons();

		add_filter( 'woocommerce_coupon_data_tabs', array( $coupons, 'register_customer_tab' ) );
		add_action( 'woocommerce_coupon_data_panels', array( $coupons, 'print_panel' ), 10, 2 );
		add_action( 'woocommerce_coupon_options_save', array( $coupons, 'save' ), 10, 2 );

		add_action( 'woocommerce_coupon_is_valid', array( $coupons, 'is_coupon_valid' ), 10, 3 );

	}
}
