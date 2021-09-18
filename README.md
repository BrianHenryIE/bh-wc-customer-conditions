[![WordPress tested 5.8](https://img.shields.io/badge/WordPress-v5.8%20tested-0073aa.svg)](#) [![PHPUnit ](.github/coverage.svg)](https://brianhenryie.github.io/bh-wc-csp-condition-ip-address/)

# Customer Conditions for WooCommerce

Adds conditions to coupons and to [WooCommerce Conditional Shipping and Payments](https://woocommerce.com/products/conditional-shipping-and-payments/).

Enables using WC_Customer properties 
* is_paying_customer()
* get_order_count()
* get_total_spend() 

e.g. restrict coupons to customers who have at least three orders and have already spent over $500.

![Coupons](./assets/screenshot-2.png "coupons")


e.g. allow Venmo payments for customers with over 5 orders and £500 already spent in the store.

![Example](./assets/screenshot-1.png "BH WC Customer Conditions screenshot")


## TODO

* Arbitrary meta key + regex.
* Investigate integration with: [WC Map Guest Orders and Downloads](https://wordpress.org/plugins/wc-map-guest-orders-and-downloads/)
* Investigate integration with: [pmgarman/wc-customer-order-index](https://github.com/pmgarman/wc-customer-order-index)
