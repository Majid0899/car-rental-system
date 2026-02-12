<?php
/**
 * Database Configuration
 * Contains all database connection settings
 */

define('DB_HOST', 'metro.proxy.rlwy.net');
define('DB_USER', 'root');
define('DB_PASS', 'nrNjZBwNQOOkhtlcrjWfjosfROfhtqDQ');
define('DB_NAME', 'railway');
define('DB_PORT', 30428);


// Application Settings
define('APP_NAME', 'Car Rental System');
define('BASE_URL', '/');

// Session Settings
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');