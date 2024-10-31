<?php // phpcs:ignore

namespace Pidex;

if (!class_exists('Installer')) {
    /**
     * Class Installer
     *
     * Sets up necessary configuration for the plugin. Takes care of database related configurations as well.
     *
     * @package Pidex
     */
    class Installer
    {
        /**
         * Run the Installer and initiate the class methods.
         *
         * @return void
         */
        public function run()
        {
            $this->manageVersion();
            $this->createApiSettingsOptions();
            $this->createShippedOrdersTable();
        }

        /**
         * Manages plugin version.
         *
         * @return void
         */
        private function manageVersion()
        {
            $installed = get_option('pidex_installed');

            if (!$installed) {
                update_option('pidex_installed', time());
            }

            update_option('pidex_version', PIDEX_VERSION);
        }

        /**
         * Create the options for Pidex API settings
         *
         * @return void
         */
        public function createApiSettingsOptions()
        {
            if (!get_option('pidex_settings')) {
                $settings = array(
                    'merchant_id' => '',
                    'api_token' => '',
                    'automatic_order_allowed' => true
                );

                add_option('pidex_settings', $settings);
            }
        }

        /**
         * Create the $wpdb->prefix . PIDEX_TABLE_PREFIX . 'shipped_orders' table to store the information of
         * successfully shipped orders to Pidex.
         *
         * @return void
         */
        public function createShippedOrdersTable()
        {
            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();
            $table_name      = $wpdb->prefix . PIDEX_TABLE_PREFIX . 'shipped_orders';

            $schema = "CREATE TABLE IF NOT EXISTS `{$table_name}` ( 
    				  	`ID` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    					`order_id` BIGINT(20) UNSIGNED NOT NULL,
    					`tracking_number` VARCHAR(191),
                        `uuid` VARCHAR(191) CHARACTER SET ASCII NOT NULL,
    					`created_by` BIGINT(20) UNSIGNED NOT NULL,
    					`created_at` TIMESTAMP NOT NULL,
    					PRIMARY KEY (`ID`)
                     ) $charset_collate;";

            if (!function_exists('dbDelta')) {
                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            }

            dbDelta($schema);
        }
    }
}
