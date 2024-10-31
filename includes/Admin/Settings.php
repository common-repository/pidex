<?php // phpcs:ignore

/**
 * This file contains the Settings class,
 * which is responsible for handling settings screen and configuration form.
 *
 * @package Pidex\Admin
 */

namespace Pidex\Admin;

if (!class_exists('Settings')) {
    /**
     * Class Settings
     *
     * Settings class handles the settings view and API configuration form.
     *
     * @package Pidex\Admin
     */
    class Settings
    {
        /**
         * Holds the API configurations stored in database.
         *
         * @var array
         */
        public $config = array();

        public function __construct()
        {
            $this->config = pidexGetSettings();
        }

        /**
         * Handles the Pidex Welcome page.
         *
         * @return void
         */
        public function loadWelcomePage()
        {
            /**
             * Load previously registered admin assets.
             */
            wp_enqueue_style('pidex-admin-style');
            wp_enqueue_script('pidex-welcome-script');

            if (!file_exists(__DIR__ . '/views/pidex-welcome-view.php')) {
                return;
            }

            include __DIR__ . '/views/pidex-welcome-view.php';
        }

        /**
         * Handles the Pidex API configuration page.
         *
         * @return void
         */
        public function loadSettingsPage()
        {
            /**
             * Load previously registered admin assets.
             */
            wp_enqueue_style('pidex-admin-style');
            wp_enqueue_script('pidex-admin-settings-script');
            wp_enqueue_style('pidex-toastify-js-style');
            wp_enqueue_script('pidex-toastify-js-script');

            if (!file_exists(__DIR__ . '/views/pidex-settings-view.php')) {
                return;
            }

            include __DIR__ . '/views/pidex-settings-view.php';
        }

        public function loadTrackingPage()
        {
            /**
             * Load previously registered admin assets.
             */
            wp_enqueue_style('pidex-tracking-style');
            wp_enqueue_script('pidex-tracking-script');

            if (!file_exists(PIDEX_PATH . '/includes/Frontend/views/pidex-tracking-view.php')) {
                return;
            }

            include PIDEX_PATH . '/includes/Frontend/views/pidex-tracking-view.php';
        }
    }
}
