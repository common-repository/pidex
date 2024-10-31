<?php
// phpcs:ignore

/**
 * This file registers custom order status `Shipped To Pidex` for WC orders.
 *
 * @package Pidex
 */

namespace Pidex;

if (!class_exists('PidexOrderStatusShippedToPidex')) {
    /**
     * Class PidexOrderStatusShippedToPidex
     *
     * The class registers the `Shipped To Pidex` order status for WC orders.
     *
     * @package Pidex\Admin
     */
    class PidexOrderStatusShippedToPidex
    {

        /**
         * PidexOrderStatusShippedToPidex constructor.
         *
         * Add necessary actions to register `Shipped To Pidex` order status.
         *
         * @return void
         */
        public function __construct()
        {
            add_action('init', array($this, 'registerShippedToPidexOrderStatus'));
            add_filter('wc_order_statuses', array($this, 'addShippedToPidexToOrderStatuses'));
        }

        /**
         * Register new post status `Shipped To Pidex` for WC orders.
         *
         * @return void
         */
        public function registerShippedToPidexOrderStatus()
        {
            register_post_status(
                'wc-shipped-to-pidex',
                array(
                    'label' => 'Shipped To Pidex',
                    'public' => true,
                    'show_in_admin_status_list' => true,
                    'show_in_admin_all_list' => true,
                    'exclude_from_search' => false,
                    'label_count' => _n_noop('Shipped To Pidex <span class="count">(%s)</span>', 'Shipped  To Pidex <span class="count">(%s)</span>'), // phpcs:ignore
                )
            );
        }

        /**
         * Add `Shipped To Pidex` post status to WC order status list.
         *
         * @param mixed $orderStatuses WC Order statuses.
         *
         * @return array
         */
        public function addShippedToPidexToOrderStatuses($orderStatuses)
        {
            $newOrderStatuses = array();
            foreach ($orderStatuses as $key => $status) {
                $newOrderStatuses[$key] = $status;
                if ('wc-processing' === $key) {
                    $newOrderStatuses['wc-shipped-to-pidex'] = 'Shipped To Pidex';
                }
            }
            return $newOrderStatuses;
        }
    }
}
