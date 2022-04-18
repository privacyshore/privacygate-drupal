PrivacyGate
=====================

# Drupal module: Commerce PrivacyGate Gateway
This module provides a Drupal Commerce payment method to embed the payment services provided by PrivacyGate.
PrivacyGate is a new service that enables merchants to accept multiple cryptocurrencies directly into a user-controlled wallet.
This module allows you to integrate PrivacyGate easily on your platform.
Additional information can be found at:
https://privacygate.io

## Required dependencies

- Commerce Payment (from [Commerce](http://drupal.org/project/commerce) core)
- Commerce Order (from [Commerce](http://drupal.org/project/commerce) core)

## Installation / Configuration

1. Download zip archive from [releases page](https://github.com/privacyshore/privacygate-drupal/releases) and unzip or clone plugin and run `composer install` inside clonned folder
2. Install the PrivacyGate Gateway module by copying the commerce_privacygate_payments to a modules directory `modules/contrib`.
3. In your Drupal site, enable the module in Drupal Extend/List find PrivacyGate, click Install button.
4. Add payment gateway at setting page and configure your API keys:
   Commerce -> Configuration -> Payment gateways -> Add payment gateway
5. Log into your PrivacyGate Dashboard and go to "Settings" section, copy the Api Key and Webhook Shared Secret from your account and paste them into the corresponding fields at the module's setup page on your Drupal site.
6. Copy the "Webhook subscription url" from your Drupal Commmerce module setup and paste it into the "Webhook Url" field at the "Notifications" section of your PrivacyGate Dashboard https://dash.privacygate.io/settings, then save the changes.

## Integrate with other e-commerce platforms

[Docs](https://privacygate.io/docs)
