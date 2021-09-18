<?php
/**
 * Bootstrap file for integration tests. Since WooCommerce needs initial activation setup.
 *
 * @package           BrianHenryIE\WC_Customer_Conditions
 */


activate_plugin( 'woocommerce/woocommerce.php' );

activate_plugin( 'woocommerce-conditional-shipping-and-payments/woocommerce-conditional-shipping-and-payments.php' );
