<?php // phpcs:ignore

/**
 * This file is responsible for registering and organizing all assets ( JavaScripts and CSS ) necessary for the Pidex plugin.
 *
 * @package Pidex
 */

namespace Pidex;

if (!class_exists('Assets')) {

    /**
     * Class Assets
     *
     * Registers necessary assets like js, css for the Pidex plugin.
     */
    class Assets
    {

        /**
         * Assets constructor.
         *
         * @return void
         */
        public function __construct()
        {
            if (is_admin()) {
                add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
            } else {
                add_action('wp_enqueue_scripts', array($this, 'enqueueAssets'));
            }
        }

        /**
         * Get an array of necessary css styles for the plugin.
         *
         * @return array[]
         */
        public function getStyles()
        {
            return array(
                'pidex-woocommerce-order-status-style' => array(
                    'src' => PIDEX_ASSETS_URL . '/css/pidex-woocommerce-order-status-style.css',
                    'deps' => array(),
                    'version' => PIDEX_VERSION,
                ),
                'pidex-admin-menu-style' => array(
                    'src' => PIDEX_ASSETS_URL . '/css/pidex-admin-menu-style.css',
                    'deps' => array(),
                    'version' => PIDEX_VERSION,
                ),
                'pidex-select2-style' => array(
                    'src' => PIDEX_ASSETS_URL . '/css/select2.min.css',
                    'deps' => array(),
                    'version' => PIDEX_VERSION,
                ),
                'pidex-toastify-js-style' => array(
                    'src' => PIDEX_ASSETS_URL . '/css/toastify.min.css',
                    'deps' => array(),
                    'version' => PIDEX_VERSION,
                ),
                'pidex-admin-style' => array(
                    'src' => PIDEX_ASSETS_URL . '/css/pidex-admin-style.css',
                    'deps' => array(),
                    'version' => PIDEX_VERSION,
                ),

                'pidex-tracking-style' => array(
                    'src' => PIDEX_ASSETS_URL . '/css/pidex-tracking-style.css',
                    'deps' => array(),
                    'version' => PIDEX_VERSION,
                ),
            );
        }

        /**
         * Get an array of necessary scripts for the plugin.
         *
         * @return array[]
         */
        public function getScripts()
        {
            return array(
                'pidex-select2-script' => array(
                    'src' => PIDEX_ASSETS_URL . '/js/select2.min.js',
                    'deps' => array('jquery'),
                    'version' => PIDEX_VERSION,
                    'in_footer' => true
                ),
                'pidex-toastify-js-script' => array(
                    'src' => PIDEX_ASSETS_URL . '/js/toastify-js.js',
                    'deps' => array(),
                    'version' => PIDEX_VERSION,
                    'in_footer' => true
                ),
                'pidex-admin-order-place-script' => array(
                    'src' => PIDEX_ASSETS_URL . '/js/pidex-admin-order-place-script.js',
                    'deps' => array('jquery'),
                    'version' => PIDEX_VERSION,
                    'in_footer' => true,
                ),
                'pidex-admin-settings-script' => array(
                    'src' => PIDEX_ASSETS_URL . '/js/pidex-admin-settings-script.js',
                    'deps' => array('jquery'),
                    'version' => PIDEX_VERSION,
                    'in_footer' => true
                ),

                'pidex-tracking-script' => array(
                    'src' => PIDEX_ASSETS_URL . '/js/pidex-tracking-script.js',
                    'deps' => array('jquery'),
                    'version' => PIDEX_VERSION,
                    'in_footer' => true
                ),

                'pidex-welcome-script' => array(
                    'src' => PIDEX_ASSETS_URL . '/js/pidex-welcome-script.js',
                    'deps' => array(),
                    'version' => PIDEX_VERSION,
                    'in_footer' => true
                ),
            );
        }

        /**
         * Register all necessary CSS and JavaScript for the plugin.
         *
         * @return void
         */
        public function enqueueAssets()
        {
            $styles = $this->getStyles();

            foreach ($styles as $handle => $style) {
                wp_register_style($handle, $style['src'], $style['deps'], $style['version']);
            }

            $scripts = $this->getScripts();

            foreach ($scripts as $handle => $script) {
                wp_register_script($handle, $script['src'], $script['deps'], $script['version'], $script['in_footer']);
            }

            wp_localize_script(
                'pidex-tracking-script',
                'PIDEX',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('pidex-tracking-nonce'),
                    'error' => __('Something went wrong!', 'pidex'),
                )
            );

            wp_localize_script(
                'pidex-admin-order-place-script',
                'PIDEX_ADMIN',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('pidex-admin-nonce'),
                    'error' => array(
                        'required' => __('All fields are required!', 'pidex'),
                    ),
                )
            );

            wp_localize_script(
                'pidex-admin-settings-script',
                'PIDEX_ADMIN',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('pidex-admin-nonce'),
                    'error' => array(
                        'required' => __('All fields are required!', 'pidex'),
                    ),
                )
            );
        }
    }
}
