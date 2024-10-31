<?php

/**
 * This will be triggered on plugin uninstall. This will remove the Pidex Settings from options table.
 *
 * @package Pidex
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

require_once __DIR__ . '/includes/Constants.php';

/**
 * Change WooCommerce Order Status from 'shipped-to-pidex' to 'processing'
 */
$orders = wc_get_orders(array(
    'status' => 'shipped-to-pidex',
));

foreach($orders as $order) {
    $order->update_status('processing');
}

/**
 * Remove Pidex API credentials
 */
if (get_option('pidex_settings')) {
    delete_option('pidex_settings');
}

/**
 * Remove Pidex Installation Unix timestamp
 */
if (get_option('pidex_installed')) {
    delete_option('pidex_installed');
}

/**
 * Remove Pidex Version
 */
if (get_option('pidex_version')) {
    delete_option('pidex_version');
}

/**
 * Drop $wpdb->prefix . PIDEX_TABLE_PREFIX . 'shipped_orders' table
 */
global $wpdb;
$tableName = $wpdb->prefix . PIDEX_TABLE_PREFIX . 'shipped_orders';
$wpdb->query("DROP TABLE IF EXISTS `{$tableName}`");
