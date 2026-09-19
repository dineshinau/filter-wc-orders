# Filter WC Orders

Filter WC Orders adds a filter dropdown to the WooCommerce orders screen in the WordPress administration area. Store administrators and shop managers can use it to find orders by payment gateway or customer type without changing the existing order workflow.

## Requirements

- WordPress 6.5 or later
- WooCommerce
- PHP 7.4 or later
- WooCommerce orders managed through either the standard order screen or High-Performance Order Storage (HPOS)

## Development Setup

### Install dependencies

From the plugin directory, install the PHP development dependencies and the npm development dependencies:

```bash
composer install
npm install
```

The Composer dependencies provide PHP_CodeSniffer, WordPress Coding Standards, WooCommerce Coding Standards, and PHP compatibility checks. The npm dependencies provide ESLint, Stylelint, Prettier integrations, and the packaging helpers used by this project.

The package also includes these equivalent npm scripts:

```bash
npm run composer_install
npm run npm_install
```

`npm run npm_install` removes `package-lock.json` before installing packages and is intended for refreshing the repository's npm dependency state. Use `npm install` when you want to preserve the existing lockfile.

### Code quality commands

PHP files are checked with the rules in `phpcs.xml`. The configured rules include WordPress Core, WordPress Extra, WordPress Docs, WooCommerce, PHPCompatibility, internationalization, security, naming, and file-format checks.

Run the configured PHP_CodeSniffer command with:

```bash
npm run phpcs
```

The script writes an XML report to `phpcs-results/`. It expects the repository's shared Composer installation to be available through the `VAR` environment variable and `$VAR/.config/composer/vendor/`. For example, when that shared installation is in `/home/developer`:

```bash
VAR=/home/developer npm run phpcs
```

To automatically apply fixes supported by PHP_CodeSniffer, run:

```bash
VAR=/home/developer npm run phpcbf
```

There are no project-specific JavaScript or CSS source files at present, but the repository retains ESLint and Stylelint configuration for future assets. The JavaScript rules extend `eslint-config-wordpress`, and the CSS rules extend `stylelint-config-wordpress` with ordered properties.

### Build a plugin ZIP

After installing dependencies and ensuring the WordPress CLI (`wp`) is available, create a distributable archive with:

```bash
npm run zip
```

This command regenerates `languages/filter-wc-orders.pot`, copies the plugin files into a temporary `filter-wc-orders` directory, creates `filter-wc-orders.zip`, and removes the temporary directory. The archive includes the admin code, translations, `index.php`, `readme.txt`, and the main plugin file.

### Project structure

```text
filter-wc-orders/
|- admin/
|  `- class-dkfwco-admin.php  # Admin dropdown and order-query filters
|- languages/
|  `- filter-wc-orders.pot    # Translation template
|- filter-wc-orders.php        # Plugin bootstrap and shared constants
|- index.php                   # Prevents directory listing access
|- readme.txt                  # WordPress.org plugin readme
|- README.md                   # Project and developer documentation
|- composer.json               # PHP development dependencies
|- package.json                # npm scripts and frontend tooling
`- phpcs.xml                   # PHP coding-standard rules
```

## How It Works

The main plugin file loads the text domain, defines plugin constants, and initializes the admin class after WordPress loads. The admin class:

1. Adds the filter dropdown to the traditional WooCommerce order list and the HPOS order list.
2. Populates payment-method choices from the payment gateways registered by WooCommerce.
3. Adds a query condition for payment methods or customer types when a filter is selected.
4. Uses the legacy post query for traditional orders and WooCommerce order-list query arguments when HPOS is enabled.

The plugin does not create a settings page or store additional plugin options. Filtering is performed using the existing WooCommerce order data.

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
