<?php
/**
 * Register the new conditions with WooCommerce Conditional Shipping and Payments.
 */
namespace BrianHenryIE\WC_Customer_Conditions\WooCommerce_Conditional_Shipping_And_Payments;

class WC_CSP_Conditions {

	/**
	 * Register the conditions with the existing set of WC CSP conditions.
	 *
	 * @hooked woocommerce_csp_conditions
	 * @see \WC_CSP_Conditions::__construct()
	 *
	 * @param string[] $conditions String array of classnames which will be instantiated.
	 *
	 * @return string[]
	 */
	public function add_conditions( $conditions ): array {

		$conditions[] = WC_CSP_Condition_Customer_Is_Paying_Customer::class;
		$conditions[] = WC_CSP_Condition_Customer_Order_Count::class;
		$conditions[] = WC_CSP_Condition_Customer_Total_Spend::class;

		return $conditions;
	}

}
