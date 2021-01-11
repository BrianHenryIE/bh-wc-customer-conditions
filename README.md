[![WordPress tested 5.5](https://img.shields.io/badge/WordPress-v5.5%20tested-0073aa.svg)](#) [![PHPUnit ](.github/coverage.svg)](https://brianhenryie.github.io/bh-wc-csp-condition-customer/)

# Customer Conditions for WooCommerce Conditional Shipping and Payments

Incomplete: shared to help someone.


[WooCommerce Conditional Shipping and Payments](https://woocommerce.com/products/conditional-shipping-and-payments/)

Enables using WC_Customer properties 
* bool: is_paying_customer()
* int: get_order_count()
* float: get_total_spend() 

e.g. hide cc gateway if order count is less than 2/

## TODO

* Reload gateways after billing email entered
* is paying customer
* total spend
* Arbitrary meta key + regex.

### Recommended 

https://wordpress.org/plugins/wc-map-guest-orders-and-downloads/
