<?php
/**
 * Database Configuration
 * Contains all database connection settings
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'car_rental_system');

// Application Settings
define('APP_NAME', 'Car Rental System');
define('BASE_URL', 'http://localhost/car-rental-system');

// Session Settings
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');