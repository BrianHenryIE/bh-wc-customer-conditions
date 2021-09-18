<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BrianHenryIE\WC_Customer_Conditions
 * @subpackage BrianHenryIE\WC_Customer_Conditions/includes
 */

namespace BrianHenryIE\WC_Customer_Conditions\Includes;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    BrianHenryIE\WC_Customer_Conditions
 * @subpackage BrianHenryIE\WC_Customer_Conditions/includes
 * @author     Brian Henry <BrianHenryIE@gmail.com>
 */
class I18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain(): void {

		load_plugin_textdomain(
			'bh-wc-customer-conditions',
			false,
			plugin_basename( dirname( __FILE__, 2 ) ) . '/languages/'
		);
	}
}
