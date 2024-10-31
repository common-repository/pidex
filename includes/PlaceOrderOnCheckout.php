<?php

/**
 * This file handles automatic order placement upon checkout (WooCommerce).
 *
 * @package Pidex
 */

namespace Pidex;

if (!class_exists('PlaceOrderOnCheckout')) {
    class PlaceOrderOnCheckout
    {
        /**
         * Plugin version
         *
         * @var string
         */
        private $orderId;

        public function __construct($orderId)
        {
            $this->orderId = $orderId;
        }

        /**
         * Handle the automatic order placement to Pidex
         *
         * @return void
         */
        public function handlePidexPlaceOrderOnWooCommerceCheckout()
        {
            $order = wc_get_order($this->orderId);

            $parcelInformation = [];
            $parcelInformation['uuid'] = wp_generate_uuid4();
            $parcelInformation['wooCommerceProductId'] = $order->get_order_number();
            $parcelInformation['merchantOrderId'] = $order->get_order_number();
            $parcelInformation['recipientName'] = '' !== trim($order->get_formatted_shipping_full_name()) ? $order->get_formatted_shipping_full_name() : $order->get_formatted_billing_full_name();
            $parcelInformation['recipientPhone'] = $order->get_billing_phone();
            $parcelInformation['recipientAddress'] = '' !== trim($order->get_shipping_address_1()) ? $order->get_shipping_address_1() . '' . $order->get_shipping_address_2() : $order->get_billing_address_1() . ' ' . $order->get_billing_address_2();
            $parcelInformation['amountToCollect'] = $order->get_total();
            $parcelInformation['quantity'] = $order->get_item_count();
            $parcelInformation['itemDescriptionAndPrice'] = '';
            $parcelInformation['wooCommerceOrderDump'] = json_encode([
                'shipping' => array_key_exists('shipping', $order->data) ? $order->data['shipping'] : [],
                'billing' => array_key_exists('billing', $order->data) ? $order->data['billing'] : [],
                'payment_method_title' => array_key_exists('payment_method_title', $order->data) ? $order->data['payment_method_title'] : [],
            ]);

            foreach ($order->get_items() as $item) {
                $parcelInformation['itemDescriptionAndPrice'] .= $item->get_name() . ' x ' . $item->get_quantity() . PHP_EOL;
            }

            /**
             * Extract and Set State
             */
            $country = '' !== trim($order->get_shipping_country()) ? $order->get_shipping_country() : $order->get_billing_country();
            $state = '' !== trim($order->get_shipping_state()) ? $order->get_shipping_state() : $order->get_billing_state();
            $parcelInformation['recipientCity'] = \WC()->countries->get_states($country)[$state];

            /**
             * Prepare API endpoint
             */
            $pidexPlaceOrderApiUrl = PIDEX_API_BASE_URL . '/place-order';

            /**
             * Send parcel booking request to Pidex.
             */
            $settings = pidexGetSettings();
            $response = wp_remote_post(
                $pidexPlaceOrderApiUrl,
                array(
                    'method' => 'POST',
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $settings['api_token'],
                        'MERCHANT-ID' => $settings['merchant_id'],
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ),
                    'body' => wp_json_encode($parcelInformation),
                )
            );

            $result = json_decode($response['body'], true);

            if ($result['success']) {
                /**
                 * Insert order shipped record to pidex_shipped_orders table.
                 */
                $insert = pidexInsertShippedOrder(
                    array(
                        'order_id' => $parcelInformation['wooCommerceProductId'],
                        'uuid' => $parcelInformation['uuid'],
                    )
                );

                /**
                 * Update the order status.
                 */
                $order->update_status('shipped-to-pidex');
            }
        }
    }
}
