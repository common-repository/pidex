<?php // phpcs:ignore

/**
 * This file contains class that handles the metabox for placing order at Pidex.
 *
 * @package Pidex\Admin
 */

namespace Pidex\Admin;

if (!class_exists('PidexPlaceOrderMetabox')) {
    /**
     * Class PidexPlaceOrderMetabox
     *
     * PidexPlaceOrderMetabox registers and handles the metabox for sending parcel booking request and placing order at Pidex.
     *
     * @package Pidex\Admin
     */
    class PidexPlaceOrderMetabox
    {

        /**
         * Holds all parcel information for Pidex.
         *
         * @var array
         */
        private $parcelInformation = array();

        private $pidexSettings = array();

        /**
         * PidexPlaceOrderMetabox constructor, fires action to add the metabox.
         *
         * @return void;
         */
        public function __construct()
        {
            add_action('add_meta_boxes', array($this, 'registerMetabox'));
            $this->pidexSettings = get_option('pidex_settings');
        }

        /**
         * The method handles the metabox registration.
         *
         * @return void;
         */
        public function registerMetabox()
        {
            add_meta_box(
                'pidex-place-order-metabox',
                __('Pidex Your Parcel', 'pidex'),
                array($this, 'metaboxViewHandler'),
                'shop_order',
                'side',
                'high'
            );
        }

        /**
         * The method is a handler for place order metabox.
         *
         * @return void
         */
        public function metaboxViewHandler()
        {
            global $theorder;

            if (!is_object($theorder)) {
                $theorder = wc_get_order(get_the_ID());
            }

            /**
             * Load previously registered admin assets.
             */
            wp_enqueue_style('pidex-select2-style');
            wp_enqueue_style('pidex-admin-style');
            wp_enqueue_style('pidex-toastify-js-style');
            wp_enqueue_script('pidex-select2-script');
            wp_enqueue_script('pidex-toastify-js-script');
            wp_enqueue_script('pidex-admin-order-place-script');

            $orderShipped = pidexGetOrderShippingInfo($theorder->get_order_number());

            if (!$orderShipped) {
                /**
                 * If order is not in $wpdb->prefix . 'pidex_shipped_orders',
                 * set all necessary Shipping Information.
                 */
                $this->setParcelInformation($theorder);
            } else {

                $wordpressUser = get_user_by('ID', $orderShipped->created_by);

                if ($wordpressUser instanceof \WP_User) {
                    $orderShipped->user = $wordpressUser->display_name;
                } else {
                    $orderShipped->user = "Pidex Automatic Booking";
                }

                if ($orderShipped->tracking_number === "") {
                    $settings = pidexGetSettings();
                    $params = [
                        'woocommerce_product_id' => $orderShipped->order_id,
                        'uuid' => $orderShipped->uuid
                    ];

                    $headers = array(
                        'Authorization' => 'Bearer ' . $settings['api_token'],
                        'MERCHANT-ID' => $settings['merchant_id'],
                        'Content-Type' => 'application/json',
                    );

                    $response = wp_remote_get(
                        PIDEX_API_BASE_URL . '/get-tracking-number' . '?' . http_build_query($params),
                        array(
                            'method' => 'GET',
                            'headers' => $headers,
                        )
                    );

                    $result = json_decode($response['body'], true);

                    if ($result['success']) {
                        /**
                         * Update Tracking Number
                         */
                        pidexUpdateTrackingNumber($result['data']['tracking_number'], $orderShipped->order_id, $result['data']['uuid']);
                    }

                    $orderShipped->tracking_number = $result['data']['tracking_number'];
                }
            }

            if ($this->pidexSettings['merchant_id'] === "" || $this->pidexSettings['api_token'] === "") {
                $apiCredentialStatus = 'empty';
            } else {
                $pidexCheckApiTokenUrl = PIDEX_API_BASE_URL . '/verify-api-token';

                $response = wp_remote_get(
                    $pidexCheckApiTokenUrl,
                    array(
                        'method' => 'GET',
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $this->pidexSettings['api_token'],
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json',
                        ),
                    )
                );

                $result = json_decode($response['body'], true);

                if (array_key_exists('success', $result) && $result['success']) {
                    $apiCredentialStatus = 'valid';
                } else {
                    $apiCredentialStatus = 'invalid';
                }
            }

            /**
             * Load the form for placing order at Pidex.
             */
            if (!file_exists(__DIR__ . '/views/pidex-place-order-metabox-view.php')) {
                return;
            }

            include __DIR__ . '/views/pidex-place-order-metabox-view.php';
        }

        /**
         * Set the shipping info to $parcelInformation variable to access from the view.
         *
         * @param \WC_Order $order holds the WooCommerce Order object.
         *
         * @return void
         */
        public function setParcelInformation(\WC_Order $order)
        {
            $this->parcelInformation['merchantOrderId'] = $order->get_order_number();

            $this->parcelInformation['recipientName'] = '' !== trim($order->get_formatted_shipping_full_name()) ? $order->get_formatted_shipping_full_name() : $order->get_formatted_billing_full_name();
            $this->parcelInformation['recipientPhone'] = $order->get_billing_phone();
            $this->parcelInformation['recipientAddress'] = '' !== trim($order->get_shipping_address_1()) ? $order->get_shipping_address_1() . '' . $order->get_shipping_address_2() : $order->get_billing_address_1() . ' ' . $order->get_billing_address_2();
            $this->parcelInformation['amountToCollect'] = $order->get_total();
            $this->parcelInformation['quantity'] = $order->get_item_count();
            $this->parcelInformation['itemDescriptionAndPrice'] = '';

            foreach ($order->get_items() as $item) {
                $this->parcelInformation['itemDescriptionAndPrice'] .= $item->get_name() . ' x ' . $item->get_quantity() . PHP_EOL;
            }

            $this->parcelInformation['wooCommerceOrderDump'] = json_encode([
                'shipping' => $order->get_data()['shipping'],
                'billing' => $order->get_data()['billing'],
                'payment_method_title' => $order->get_data()['payment_method_title'],
            ]);
        }

        public function isParcelInformationSet()
        {
            return count($this->parcelInformation) != 0;
        }
    }
}
