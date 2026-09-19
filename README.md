# Filter WC Orders

Filter WC Orders adds a filter dropdown to the WooCommerce orders screen in the WordPress administration area. Store administrators and shop managers can use it to find orders by payment gateway or customer type without changing the existing order workflow.

## Requirements

- WordPress 6.5 or later
- WooCommerce
- PHP 7.4 or later
- WooCommerce orders managed through either the standard order screen or High-Performance Order Storage (HPOS)

## Features

### Filter by payment gateway

The plugin lists the payment gateways installed on the site in the **By Payment Method** group. Select a gateway to display orders that were processed through that payment method.

The available payment methods are generated from the gateways currently registered by WooCommerce, so the list reflects the payment configuration of each site.

### Filter by customer type

The **By User Types** group provides filters for:

- **Guest Users** - orders placed without a registered customer account
- **Logged in Users** - orders associated with a registered customer account

### Works with WooCommerce order storage

The order filter is available on the WooCommerce orders screen and supports sites using the traditional order data store or HPOS. The plugin adds its filter alongside the existing WooCommerce administration controls.

## Installation

1. Upload the `filter-wc-orders` directory to `/wp-content/plugins/`, or install the plugin through the WordPress admin plugin installer.
2. In WordPress, open **Plugins** and activate **Filter WC Orders**.
3. Open **WooCommerce > Orders** in the WordPress admin dashboard.
4. Open the **Filter WC Orders** dropdown, choose a payment method or customer type, and apply the filter.
5. Select **No filter** to return to the complete order list.

## Usage

After activation, the new dropdown appears on the WooCommerce orders administration screen. Choose one of the available options and apply the standard WordPress or WooCommerce list filter. The selected option remains active while viewing the filtered results.

Payment gateway options use the payment method names configured by WooCommerce. If a gateway is installed or removed, the available options are updated automatically.

## Frequently Asked Questions

### What types of filters does this plugin provide?

Administrators and shop managers can filter orders by any payment gateway installed on the site, or by customer type: guest users and logged-in users.

### Where can I find the filter?

Go to **WooCommerce > Orders** in the WordPress admin dashboard. The filter is displayed with the other order-list controls.

### Does the plugin add a separate settings page?

No. The plugin works directly on the WooCommerce orders screen and does not require a separate settings page.

### Does it work with HPOS?

Yes. Filter WC Orders includes support for WooCommerce's High-Performance Order Storage order list as well as the traditional order screen.

## Screenshots

1. **Filter Location** - The Filter WC Orders dropdown on the WooCommerce orders screen.

	`screenshot-1.png`

## Connect with me

- [Website](https://dineshinaublog.wordpress.com/)
- [LinkedIn](https://www.linkedin.com/in/dineshinau/)
- [Facebook](https://www.facebook.com/dineshinau/)
- [X](https://x.com/dineshinau/)

## Changelog

### 1.0.4 - 2026-09-20

- Updated the coding structure according to PHPCS 3.8.0.

### 1.0.3 - 2024-01-12

- Updated the coding structure according to PHPCS 3.8.0.

### 1.0.2 - 2023-08-15

- Tested up to WordPress 6.3.

### 1.0.1 - 2022-05-08

- Tested up to WordPress 6.0.

### 1.0 - 2021-07-24

- Initial release of the plugin.

## License

Filter WC Orders is licensed under the GPLv3 or later. See the [GNU General Public License](http://www.gnu.org/licenses/gpl-3.0.html) for details.
