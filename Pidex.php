<?php

/**
 * Plugin Name:       Pidex
 * Plugin URI:        https://pidex.biz/wordpress
 * Description:       Pidex enables you to send a parcel booking request to Pidex directly from your WooCommerce orders or automatically after checkout.
 * Version:           1.0.1
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Pidex Infosys
 * Author URI:        https://pidex.biz/pidexinfosys
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pidex
 * Domain Path:       /languages
 * @package           Pidex
 */

/**
 * Copyright (c) 2023 Pidex Infosys (email: fardeen@pidex.biz). All rights reserved.
 *
 * Released under the GPL license
 * https://www.gnu.org/licenses/gpl-3.0.html
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */


/**
 * Block direct access to file
 */
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/includes/Admin/Menu.php';
require_once __DIR__ . '/includes/Admin/PidexPlaceOrderMetabox.php';
require_once __DIR__ . '/includes/Admin/Settings.php';
require_once __DIR__ . '/includes/Admin.php';
require_once __DIR__ . '/includes/Ajax.php';
require_once __DIR__ . '/includes/Assets.php';
require_once __DIR__ . '/includes/Constants.php';
require_once __DIR__ . '/includes/Frontend.php';
require_once __DIR__ . '/includes/Frontend/PidexTrackerShortcode.php';
require_once __DIR__ . '/includes/Functions.php';
require_once __DIR__ . '/includes/Installer.php';
require_once __DIR__ . '/includes/PidexOrderStatusShippedToPidex.php';
require_once __DIR__ . '/includes/PlaceOrderOnCheckout.php';

if (!class_exists('Pidex')) {
    /**
     * Register the main plugin class.
     *
     * Class Pidex
     */
    final class Pidex
    {
        /**
         * Pidex constructor.
         *
         * @return void
         */
        public function __construct()
        {
            register_activation_hook(__FILE__, array($this, 'activate'));

            add_action('plugins_loaded', array($this, 'initPlugin'));

            $pidexSettings = get_option('pidex_settings');

            /**
             * Check if the WooCommerce plugin is active then add action to place orders automatically
             * after checking out
             */
            if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {

                if ($pidexSettings == true && $pidexSettings['automatic_order_allowed']) {
                    /**
                     * @param $orderId
                     * @return void
                     */
                    function placeOrderToPidex($orderId)
                    {
                        $placeOrderOnCheckout = new Pidex\PlaceOrderOnCheckout($orderId);
                        $placeOrderOnCheckout->handlePidexPlaceOrderOnWooCommerceCheckout();
                    }

                    add_action('woocommerce_thankyou', 'placeOrderToPidex');
                }
            }

            add_action( 'activated_plugin', array($this,'pidexActivationRedirect') );
        }

        /**
         * Redirect to Welcome page after plugin activation
         *
         * @retun void
         */
        public function pidexActivationRedirect( $plugin ) {
            if( $plugin == plugin_basename( __FILE__ ) ) {
                exit( wp_redirect( admin_url('admin.php?page=pidex') ) );
            }
        }

        /**
         * Load Style for WooCommerce Status Badge
         *
         * @retun void
         */
        public function enqueueWooCommerceStatusBadgeStyle()
        {
            wp_enqueue_style('pidex-woocommerce-order-status-style');
        }

        /**
         * Load Style for Admin Menu
         *
         * @retun void
         */
        public function enqueueAdminMenuStyle()
        {
            wp_enqueue_style('pidex-admin-menu-style');
        }


        /**
         * Initialize a singleton instance.
         *
         * @return \Pidex
         */
        public static function init()
        {
            $instance = false;

            if (!$instance) {
                $instance = new self();
            }

            return $instance;
        }

        /**
         * Load plugin classes and initialize assets.
         *
         * @return void
         */
        public function initPlugin()
        {
            /**
             * Call Assets class to load necessary assets for plugin ( JavaScript and CSS ).
             */
            new Pidex\Assets();

            /**
             * Load Ajax request handler.
             */
            if (defined('DOING_AJAX') && DOING_AJAX) {
                new Pidex\Ajax();
            }

            if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {
                new Pidex\PidexOrderStatusShippedToPidex();
            }

            if (is_admin()) {
                /**
                 * Add WooCommerce Status Badge Style
                 */
                add_action('admin_enqueue_scripts', array($this, 'enqueueWooCommerceStatusBadgeStyle'));

                /**
                 * Add Admin Menu Style
                 */
                add_action('admin_enqueue_scripts', array($this, 'enqueueAdminMenuStyle'));

                /**
                 * Load admin classes.
                 */
                new Pidex\Admin();
            } else {
                /**
                 * Load Frontend classes.
                 */
                new Pidex\Frontend();
            }
        }

        /**
         * Necessary setup on plugin activation.
         *
         * @return void
         */
        public function activate()
        {
            $installer = new \Pidex\Installer();
            $installer->run();
        }
    }
}

/**
 * Initialize the main plugin.
 *
 * @return \Pidex|bool
 */
function pidex()
{
    return Pidex::init();
}

/**
 * Start the plugin.
 */
pidex();
