<?php

/**
 * Fetch Pidex API credentials from database.
 *
 * @return array
 */
function pidexGetSettings()
{
    /**
     * Get and return Pidex API configurations from options table.
     */
    return get_option('pidex_settings');
}

/**
 * Insert successfully shipped order information to database.
 *
 * @param array $args shipped order information.
 *
 * @return bool|\WP_Error
 */
function pidexInsertShippedOrder($args = array())
{
    global $wpdb;

    $tableName = $wpdb->prefix . PIDEX_TABLE_PREFIX . 'shipped_orders';

    $defaults = array(
        'order_id' => '',
        'tracking_number' => '',
        'uuid' => '',
        'created_by' => get_current_user_id(),
        'created_at' => wp_date('Y-m-d H:i:s', null, new \DateTimezone('Asia/Dhaka')),
    );

    $data = wp_parse_args($args, $defaults);

    $inserted = $wpdb->insert(
        $tableName,
        $data,
        array('%d', '%s', '%s', '%d', '%s')
    );

    if (!$inserted) {
        return new \WP_Error('failed-to-insert-shipped-order', __('Failed to insert shipped order information!', 'pidex'));
    }

    return true;
}

/**
 * Update successfully shipped order tracking number.
 *
 * @param string $trackingNumber parcel tracking number.
 *
 * @return bool|\WP_Error
 */
function pidexUpdateTrackingNumber($trackingNumber, $orderId, $uuid)
{
    global $wpdb;

    $tableName = $wpdb->prefix . PIDEX_TABLE_PREFIX . 'shipped_orders';

    $data = array(
        'tracking_number' => $trackingNumber
    );

    $where = array(
        'order_id' => $orderId,
        'uuid' => $uuid
    );

    $updated = $wpdb->update($tableName, $data, $where);

    if ($updated === false) {
        return new \WP_Error('failed-to-update-tracking-number', __('Failed to update tracking number!', 'pidex'));
    }

    return true;
}

/**
 * Handles the database insertion for API credentials.
 *
 * @param array $args array of API credentials.
 *
 * @return \WP_Error|bool
 */
function pidexInsertSettings($args = array())
{
    if (empty($args['merchant_id']) || empty($args['api_token'])) {
        return new \WP_Error('required-field-missing', __('All fields are required.', 'pidex'));
    }

    $inserted = update_option('pidex_settings', $args);

    if (!$inserted) {
        return new \WP_Error('failed-to-insert', __('Failed to insert settings data', 'pidex'));
    }

    return true;
}

/**
 * Check if the order is already Shipped To Pidex and return the status.
 *
 * @param int $order_id WC order number.
 *
 * @return array|object|bool
 */
function pidexGetOrderShippingInfo($orderId)
{
    global $wpdb;

    $tableName = $wpdb->prefix . PIDEX_TABLE_PREFIX . 'shipped_orders';

    $orderShipmentStatus = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $tableName WHERE order_id = %s",
            $orderId
        )
    );

    if (empty($orderShipmentStatus)) {
        return false;
    }

    return $orderShipmentStatus[0];
}
