<?php // phpcs:ignore

/**
 * This file handles all the necessary Ajax requests for the `Pidex` plugin.
 *
 * @package Pidex
 */

namespace Pidex;

if (!class_exists('Ajax')) {
    /**
     * Class Ajax
     *
     * Handles all the necessary Ajax requests, API for submissions and returns the necessary data.
     *
     * @package Pidex
     */
    class Ajax
    {

        /**
         * Contains Pidex API base url.
         *
         * @var string
         */
        public $pidexApiBaseUrl = '';

        /**
         * Contains Pidex API credentials.
         *
         * @var array
         */
        public $settings = array();

        /**
         * Ajax constructor, registers the actions.
         *
         * @return void
         */
        public function __construct()
        {

            /**
             * Get Pidex API credentials
             */
            $this->settings = pidexGetSettings();

            /**
             * Set Pidex Base URL
             */
            $this->pidexApiBaseUrl = PIDEX_API_BASE_URL;

            /**
             * Pidex Settings
             */
            add_action('wp_ajax_pidex_verify_merchant_id', array($this, 'handlePidexVerifyMerchantId'));
            add_action('wp_ajax_pidex_submit_settings', array($this, 'handlePidexSubmitSettings'));

            /**
             * Pidex Place Order Metabox
             */
            add_action('wp_ajax_pidex_fetch_merchant_city', array($this, 'handlePidexFetchMerchantCity'));
            add_action('wp_ajax_pidex_fetch_cities', array($this, 'handlePidexFetchCities'));
            add_action('wp_ajax_pidex_fetch_zones', array($this, 'handlePidexFetchZones'));
            add_action('wp_ajax_pidex_fetch_delivery_types', array($this, 'handlePidexFetchDeliveryTypes'));
            add_action('wp_ajax_pidex_place_order_metabox_form', array($this, 'handlePidexPlaceOrderMetaboxForm'));
            add_action('wp_ajax_pidex_submit_tracking', array($this, 'handlePidexSubmitTracking'));
            add_action('wp_ajax_nopriv_pidex_submit_tracking', array($this, 'handlePidexSubmitTracking'));
        }

        /**
         * Varifies Pidex Merchant ID
         *
         * @return void
         */
        public function handlePidexVerifyMerchantId()
        {
            if (!isset($_GET['verify_pidex_merchant_id']) || !isset($_GET['_nonce'])) {
                die('Request is not valid!');
            }

            /**
             * Block if _nonce field is not available and valid.
             */
            check_ajax_referer('pidex-admin-nonce', '_nonce');

            /**
             * Prepare API endpoint
             */
            $pidexVerifyMerchantIDApiUrl = $this->pidexApiBaseUrl . '/verify-merchant-id';

            /**
             * Sanitize & Send fetch cities request to Pidex.
             */
            $data = array(
                'merchantId' => sanitize_text_field(wp_unslash($_GET['merchantId'])),
            );
            $response = $this->makeRequest($pidexVerifyMerchantIDApiUrl, 'GET', $data);

            $result = json_decode($response['body'], true);

            if ($result['success']) {
                /**
                 * Send response back to admin panel.
                 */
                wp_send_json_success(
                    array(
                        'message' => array_key_exists('data', $result) ? $result['data'] : [],
                    )
                );
            }

            /**
             * If not success send error back to admin panel.
             */
            wp_send_json_error(
                array(
                    'body' => array_key_exists('data', $result) ? $result['data'] : [],
                )
            );
        }

        /**
         * Handles Pidex Settings Submission and Saves it in the Database
         *
         * @return void
         */
        public function handlePidexSubmitSettings()
        {
            if (!isset($_POST['submit_pidex_settings']) || !isset($_POST['_nonce'])) {
                die('Request is not valid!');
            }

            /**
             * Block if _nonce field is not available and valid.
             */
            check_ajax_referer('pidex-admin-nonce', '_nonce');


            /**
             * Sanitize and Insert
             */
            $result = pidexInsertSettings(
                array(
                    'merchant_id' => sanitize_text_field(wp_unslash($_POST['merchantId'])),
                    'api_token' => sanitize_text_field($_POST['apiToken']),
                    'automatic_order_allowed' => sanitize_text_field($_POST['automaticOrderAllowed']) === 'true',
                )
            );

            if (is_wp_error($result)) {
                /**
                 * If not success send error back to admin panel.
                 */
                wp_send_json_error(
                    array(
                        'body' => $result->get_error_message(),
                    )
                );
            }

            /**
             * Send response back to admin panel.
             */
            wp_send_json_success(
                array(
                    'message' => 'Settings Saved Successfully',
                )
            );
        }

        /**
         * Fetches merchant city
         *
         * @return void
         */
        public function handlePidexFetchMerchantCity()
        {
            if (!isset($_GET['pidex_fetch_merchant_city']) || !isset($_GET['_nonce'])) {
                die('Request is not valid!');
            }

            /**
             * Block if _nonce field is not available and valid.
             */
            check_ajax_referer('pidex-admin-nonce', '_nonce');

            /**
             * Prepare API endpoint
             */
            $pidexFetchMerchantCityApiUrl = $this->pidexApiBaseUrl . '/merchant-city';

            /**
             * Send fetch cities request to Pidex.
             */
            $response = $this->makeRequest($pidexFetchMerchantCityApiUrl, 'GET', array());

            $result = json_decode($response['body'], true);

            if ($result['success']) {
                /**
                 * Send response back to admin panel.
                 */
                wp_send_json_success(
                    array(
                        'message' => array_key_exists('data', $result) ? $result['data'] : [],
                    )
                );
            }

            /**
             * If not success send error back to admin panel.
             */
            wp_send_json_error(
                array(
                    'body' => array_key_exists('data', $result) ? $result['data'] : [],
                )
            );
        }

        /**
         * Fetches available cities
         *
         * @return void
         */
        public function handlePidexFetchCities()
        {
            if (!isset($_GET['pidex_fetch_cities']) || !isset($_GET['_nonce'])) {
                die('Request is not valid!');
            }

            /**
             * Block if _nonce field is not available and valid.
             */
            check_ajax_referer('pidex-admin-nonce', '_nonce');

            /**
             * Prepare API endpoint
             */
            $pidexFetchCitiesApiUrl = $this->pidexApiBaseUrl . '/cities';

            /**
             * Send fetch cities request to Pidex.
             */
            $response = $this->makeRequest($pidexFetchCitiesApiUrl, 'GET', []);

            $result = json_decode($response['body'], true);

            if (array_key_exists('success', $result) && $result['success']) {
                /**
                 * Send response back to admin panel.
                 */
                wp_send_json_success(
                    array(
                        'message' => array_key_exists('data', $result) ? $result['data'] : [],
                    )
                );
            }

            /**
             * If not success send error back to admin panel.
             */
            wp_send_json_error(
                array(
                    'body' => array_key_exists('data', $result) ? $result['data'] : [],
                )
            );
        }

        /**
         * Fetches available zones for given City
         *
         * @return void
         */
        public function handlePidexFetchZones()
        {
            if (!isset($_GET['pidex_fetch_zones']) || !isset($_GET['_nonce'])) {
                die('Request is not valid!');
            }

            /**
             * Block if _nonce field is not available and valid.
             */
            check_ajax_referer('pidex-admin-nonce', '_nonce');

            /**
             * Prepare API endpoint
             */
            $pidexFetchZonesApiUrl = $this->pidexApiBaseUrl . '/zones';

            /**
             * Send fetch zones request to Pidex.
             */
            $data = array(
                'selectedCity' => sanitize_text_field(wp_unslash($_GET['selectedCity'])),
            );
            $response = $this->makeRequest($pidexFetchZonesApiUrl, 'GET', $data);

            $result = json_decode($response['body'], true);

            if ($result['success']) {
                /**
                 * Send response back to admin panel.
                 */
                wp_send_json_success(
                    array(
                        'message' => array_key_exists('data', $result) ? $result['data'] : [],
                    )
                );
            }

            /**
             * If not success send error back to admin panel.
             */
            wp_send_json_error(
                array(
                    'body' => array_key_exists('data', $result) ? $result['data'] : [],
                )
            );
        }

        /**
         * Fetches available delivery types
         *
         * @return void
         */
        public function handlePidexFetchDeliveryTypes()
        {
            if (!isset($_GET['pidex_fetch_delivery_types']) || !isset($_GET['_nonce'])) {
                die('Request is not valid!');
            }

            /**
             * Block if _nonce field is not available and valid.
             */
            check_ajax_referer('pidex-admin-nonce', '_nonce');

            /**
             * Prepare API endpoint
             */
            $pidexFetchDeliveryTypesApiUrl = $this->pidexApiBaseUrl . '/delivery-types';

            /**
             * Send fetch delivery types request to Pidex.
             */
            $data = array();

            $response = $this->makeRequest($pidexFetchDeliveryTypesApiUrl, 'GET', $data);

            $result = json_decode($response['body'], true);

            if (array_key_exists('success', $result) && $result['success']) {
                /**
                 * Send response back to admin panel.
                 */
                wp_send_json_success(
                    array(
                        'message' => array_key_exists('data', $result) ? $result['data'] : [],
                    )
                );
            }

            /**
             * If not success send error back to admin panel.
             */
            wp_send_json_error(
                array(
                    'body' => array_key_exists('data', $result) ? $result['data'] : [],
                )
            );
        }

        /**
         * Handle the place order metabox form submission, and generate a parcel booking to Pidex.
         *
         * @return void
         */
        public function handlePidexPlaceOrderMetaboxForm()
        {
            if (!isset($_POST['submit_pidex_order_place']) || !isset($_POST['_nonce'])) {
                die('Request is not valid!');
            }

            /**
             * Block if _nonce field is not available and valid.
             */
            check_ajax_referer('pidex-admin-nonce', '_nonce');

            /**
             * Post data validation.
             */
            $errors = array();

            if (!isset($_POST['recipientName']) || '' === $_POST['recipientName']) {
                $errors['recipient_name'] = __('Please enter Recipient Name', 'pidex');
            }
            if (!isset($_POST['recipientPhone']) || '' === $_POST['recipientPhone']) {
                $errors['recipient_phone'] = __('Please enter Recipient Phone', 'pidex');
            }
            if (!isset($_POST['recipientAddress']) || '' === $_POST['recipientAddress']) {
                $errors['recipient_address'] = __('Please enter Recipient Address', 'pidex');
            }
            if (!isset($_POST['amountToCollect']) || '' === $_POST['amountToCollect']) {
                $errors['amount_to_collect'] = __('Please enter Collection Amount', 'pidex');
            }
            if (!isset($_POST['recipientCity']) || '' === $_POST['recipientCity']) {
                $errors['recipient-city-select'] = __('Please enter Recipient City', 'pidex');
            }
            if (!isset($_POST['recipientZone']) || '' === $_POST['recipientZone']) {
                $errors['recipient-zone-select'] = __('Please enter Recipient Zone', 'pidex');
            }
            if (!isset($_POST['deliveryType']) || '' === $_POST['deliveryType']) {
                $errors['recipient-delivery-type-select'] = __('Please enter Delivery Type', 'pidex');
            }

            if (count($errors) != 0) {
                wp_send_json_error(
                    $errors
                );
            }

            /**
             * Prepare parcel data before sending it to Pidex.
             */
            $parcelData = array(
                'uuid' => wp_generate_uuid4(),
                'wooCommerceProductId' => sanitize_text_field(wp_unslash($_POST['wooCommerceProductId'])),
                'merchantOrderId' => sanitize_text_field(wp_unslash($_POST['merchantOrderId'])),
                'recipientName' => sanitize_text_field(wp_unslash($_POST['recipientName'])),
                'recipientPhone' => sanitize_text_field(wp_unslash($_POST['recipientPhone'])),
                'recipientAddress' => sanitize_text_field(wp_unslash($_POST['recipientAddress'])),
                'amountToCollect' => sanitize_text_field(wp_unslash($_POST['amountToCollect'])),
                'recipientCity' => sanitize_text_field(wp_unslash($_POST['recipientCity'])),
                'recipientZone' => sanitize_text_field(wp_unslash($_POST['recipientZone'])),
                'deliveryType' => sanitize_text_field(wp_unslash($_POST['deliveryType'])),
                'quantity' => sanitize_text_field(wp_unslash($_POST['quantity'])),
                'specialInstruction' => sanitize_text_field(wp_unslash($_POST['specialInstruction'])),
                'itemDescriptionAndPrice' => sanitize_text_field(wp_unslash($_POST['itemDescriptionAndPrice'])),
                'wooCommerceOrderDump' => sanitize_text_field(wp_unslash($_POST['wooCommerceOrderDump'])),
            );

            /**
             * Prepare API endpoint
             */
            $pidexPlaceOrderApiUrl = $this->pidexApiBaseUrl . '/place-order';

            /**
             * Send parcel booking request to Pidex.
             */
            $response = $this->makeRequest($pidexPlaceOrderApiUrl, 'POST', $parcelData);

            $result = json_decode($response['body'], true);


            if ($result['success']) {
                /**
                 * Insert order shipped record to pidex_shipped_orders table.
                 */
                $insert = pidexInsertShippedOrder(
                    array(
                        'order_id' => $parcelData['wooCommerceProductId'],
                        'uuid' => $parcelData['uuid'],
                    )
                );

                /**
                 * If WP_Error send error message back to admin panel.
                 */
                if (is_wp_error($insert)) {
                    wp_send_json_error(
                        array(
                            'message' => $insert->get_error_message(),
                        )
                    );
                }

                /**
                 * Get the order to update the order status.
                 */
                $order = new \WC_Order($parcelData['wooCommerceProductId']);
                $order->update_status('shipped-to-pidex');

                /**
                 * Send response back to admin panel.
                 */
                wp_send_json_success(
                    array(
                        'message' => array_key_exists('body', $result) ? $result['body'] : [],
                    )
                );
            }

            /**
             * If not success send error back to admin panel.
             */
            wp_send_json_error(
                array(
                    'body' => $result,
                )
            );
        }

        /**
         * Handle the tracking form submission. Get parcel status from Pidex and return to front end.
         *
         * @return void
         */
        public function handlePidexSubmitTracking()
        {
            if (!isset($_GET['submit_pidex_tracking']) || !isset($_GET['_nonce'])) {
                die('Request is not valid!');
            }

            /**
             * Block if valid nonce field is not available and valid.
             */
            check_ajax_referer('pidex-tracking-nonce', '_nonce');

            if (isset($_GET['trackingNumber'])) {

                $trackingNumber = sanitize_text_field(wp_unslash($_GET['trackingNumber']));

                $pidexTrackingApiUrl = $this->pidexApiBaseUrl . '/track';

                /**
                 * Make request to Pidex API.
                 */
                $response = $this->makeRequest($pidexTrackingApiUrl, 'GET', array('trackingNumber' => $trackingNumber));
                $result = json_decode($response['body'], true);

                if ($result['success']) {
                    /**
                     * Send response to front-end.
                     */
                    wp_send_json_success(
                        array(
                            'message' => array_key_exists('data', $result) ? $result['data'] : [],
                        )
                    );
                }

                wp_send_json_error(
                    array(
                        'message' => $result,
                    )
                );
            }

            wp_send_json_error(
                array(
                    'message' => __('Please provide a valid tracking code.', 'pidex'),
                )
            );
        }

        /**
         * Make request to Pidex API.
         *
         * @param string $url API end-point.
         * @param array $params API request parameters.
         * @param string $method HTTP request method.
         *
         * @return array|\WP_Error
         */
        public function makeRequest($url, $method, $params = array())
        {
            $headers = array(
                'Authorization' => 'Bearer ' . $this->settings['api_token'],
                'MERCHANT-ID' => $this->settings['merchant_id'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            );

            switch ($method) {
                case 'GET':
                    return wp_remote_get(
                        $url . '?' . http_build_query($params),
                        array(
                            'method' => 'GET',
                            'headers' => $headers,
                        )
                    );

                case 'POST':
                    return wp_remote_post(
                        $url,
                        array(
                            'method' => 'POST',
                            'headers' => $headers,
                            'body' => wp_json_encode($params),
                        )
                    );
            }
        }
    }
}
