<?php
/**
 * Logout Script
 * Destroys session and redirects to home page
 */

require_once __DIR__ . '/controllers/AuthController.php';

$auth = new AuthController();
$auth->logout();