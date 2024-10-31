<?php

define('PIDEX_VERSION', '1.0.0');
define('PIDEX_FILE', realpath(__DIR__ . '/../Pidex.php'));
define('PIDEX_PATH', realpath(__DIR__ . '/../'));
define('PIDEX_URL', plugins_url('', PIDEX_FILE));
define('PIDEX_ASSETS_URL', PIDEX_URL . '/assets');
define('PIDEX_TABLE_PREFIX', 'pidex_');
define('PIDEX_BASE_URL', 'http://www.pidex.biz');
define('PIDEX_API_BASE_URL', PIDEX_BASE_URL . '/api/wordpress');
define('PIDEX_BASE_TRACKING_URL', PIDEX_BASE_URL . '/track');
define('PIDEX_BASE_ORDER_DETAILS_URL', PIDEX_BASE_URL . '/merchant/order');
define('PIDEX_MERCHANT_API_TOKEN_URL', PIDEX_BASE_URL . '/merchant/api-token');
// define('PIDEX_ORDER_STATUS_KEY', '');
// define('PIDEX_ORDER_STATUS_VALUE', '');
