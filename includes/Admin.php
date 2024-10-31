<?php // phpcs:ignore

/**
 * This file handles, loads and organizes the admin-end related classes and functionalities.
 *
 * @package Pidex
 */

namespace Pidex;

if (!class_exists('Admin')) {
    /**
     * Class Admin
     *
     * Initializes all admin-end classes and functionalities.
     *
     * @package Pidex
     */
    class Admin
    {

        /**
         * Admin constructor.
         *
         * @return void
         */
        public function __construct()
        {
            $settings = new Admin\Settings();

            $this->showAdminAlert();

            new Admin\Menu($settings);

            /**
             * Check if the WooCommerce plugin is active then fire the Pidex_Metabox class to add the custom metabox.
             * Also call the Pidex_Order_Status_Shipped class to add `Shipped` status to WC order statuses.
             */
            if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {
                new Admin\PidexPlaceOrderMetabox();
            }
        }

        /**
         * Show Alerts to Admin
         *
         * @retun void
         */
        public function showAdminAlert()
        {
//            add_action('admin_notices', array($this, 'alertAPITokenIncorrect'));
        }
    }
}
