<?php // phpcs:ignore

/**
 * This file handles, loads and organizes the frontend (non-admin) related classes and functionalities.
 *
 * @package Pidex
 */

namespace Pidex;

if (!class_exists('Frontend')) {
    /**
     * Class Frontend
     *
     * Initializes all frontend related classes.
     *
     * @package Pidex
     */
    class Frontend
    {

        /**
         * Frontend constructor.
         *
         * @return void
         */
        public function __construct()
        {
            new Frontend\PidexTrackerShortcode();
        }
    }
}
