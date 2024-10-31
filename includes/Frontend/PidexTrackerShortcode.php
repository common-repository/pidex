<?php // phpcs:ignore
/**
 * This file registers and handles the shortcode for Pidex Tracker.
 *
 * @package Pidex\Frontend
 */
namespace Pidex\Frontend;

if ( ! class_exists( 'PidexTrackerShortcode' ) ) {
    /**
     * Class PidexTrackerShortcode
     *
     * The class handles the frontend shortcode for Pidex Tracker.
     *
     * @package Pidex\Frontend
     */
    class PidexTrackerShortcode {

        /**
         * PidexTrackerShortcode constructor.
         *
         * @return void
         */
        public function __construct() {
            add_shortcode( 'pidex_tracker', array( $this, 'renderShortcode' ) );
        }

        /**
         * Render the Pidex Tracker frontend using a shortcode.
         *
         * @return string
         */
        public function renderShortcode() {

            /**
             * Load previously registered tracker assets.
             */
            wp_enqueue_style('pidex-tracking-style');
            wp_enqueue_script('pidex-tracking-script');

            ob_start();

            include __DIR__ . '/views/pidex-tracking-view.php';

            return ob_get_clean();
        }
    }
}
