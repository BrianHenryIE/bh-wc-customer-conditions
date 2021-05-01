<?php
/**
 * When billing_email is changed, the available shipping & gateways should be updated.
 *
 * Use `update_totals_on_change` CSS class on `billing_email` field.
 *
 * @see WC_Countries::get_address_fields()
 *
 * Maybe this class should be called 'Countries'.
 */

namespace BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce;

class Checkout {

	/**
	 * TODO: This should only be added when the condition is enabled.
	 *
	 * @see https://github.com/woocommerce/woocommerce/issues/12349
	 *
	 * @hooked woocommerce_billing_fields
	 *
	 * @param array<string, array<string, mixed>> $address_fields
	 * @param string                              $country
	 * @return array<string, array<string, mixed>>
	 */
	public function add_update_totals_class_to_billing_email( array $address_fields, string $country ): array {

		if ( isset( $address_fields['billing_email'] ) && isset( $address_fields['billing_email']['class'] ) ) {
			if ( ! in_array( 'update_totals_on_change', $address_fields['billing_email']['class'], true ) ) {
				$address_fields['billing_email']['class'][] = 'update_totals_on_change';
			}
		}

		return $address_fields;
	}

}
