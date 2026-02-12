<?php
/**
 * Auth Controller
 * Handles authentication and authorization
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->initSession();
    }
    
    /**
     * Initialize session
     */
    private function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Register customer
     */
    public function registerCustomer() {
        $errors = [];
        $success = false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => $this->sanitize($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'full_name' => $this->sanitize($_POST['full_name'] ?? ''),
                'phone' => $this->sanitize($_POST['phone'] ?? ''),
                'address' => $this->sanitize($_POST['address'] ?? '')
            ];
            
            // Validate data
            $errors = $this->userModel->validateUserData($data, 'customer');
            
            if (empty($errors)) {
                $userId = $this->userModel->registerCustomer($data);
                
                if ($userId) {
                    $success = true;
                    $_SESSION['success_message'] = "Registration successful! Please login.";
                    header('Location: login.php');
                    exit;
                } else {
                    $errors[] = "Registration failed. Please try again.";
                }
            }
        }
        
        return ['errors' => $errors, 'success' => $success];
    }
    
    /**
     * Register agency
     */
    public function registerAgency() {
        $errors = [];
        $success = false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => $this->sanitize($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'full_name' => $this->sanitize($_POST['full_name'] ?? ''),
                'phone' => $this->sanitize($_POST['phone'] ?? ''),
                'address' => $this->sanitize($_POST['address'] ?? ''),
                'agency_name' => $this->sanitize($_POST['agency_name'] ?? ''),
                'license_number' => $this->sanitize($_POST['license_number'] ?? '')
            ];
            
            // Validate data
            $errors = $this->userModel->validateUserData($data, 'agency');
            
            if (empty($errors)) {
                $userId = $this->userModel->registerAgency($data);
                
                if ($userId) {
                    $success = true;
                    $_SESSION['success_message'] = "Registration successful! Please login.";
                    header('Location: login.php');
                    exit;
                } else {
                    $errors[] = "Registration failed. Please try again.";
                }
            }
        }
        
        return ['errors' => $errors, 'success' => $success];
    }
    
    /**
     * Login
     */
    public function login() {
    $errors = [];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $this->sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $errors[] = "Email and password are required";
        } else {
            $user = $this->userModel->login($email, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_type'] = $user->user_type;
                $_SESSION['user_name'] = $user->full_name;
                $_SESSION['user_email'] = $user->email;
                
                if ($user->user_type === 'agency') {
                    header('Location: /views/agency/add-car.php');
                } else {
                    if (isset($_SESSION['redirect_after_login'])) {
                        $redirect = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']);
                        header('Location: ' . $redirect);
                    } else {
                        header('Location: /views/customer/available-cars.php');
                    }
                }
                exit;
            } else {
                $errors[] = "Invalid email or password";
            }
        }
    }
    
    return ['errors' => $errors];
}

    
    /**
     * Logout
     */
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Check if user is customer
     */
    public function isCustomer() {
        return $this->isLoggedIn() && $_SESSION['user_type'] === 'customer';
    }
    
    /**
     * Check if user is agency
     */
    public function isAgency() {
        return $this->isLoggedIn() && $_SESSION['user_type'] === 'agency';
    }
    
    /**
     * Require login
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /views/auth/login.php');
            exit;
        }
    }
    
    /**
     * Require customer
     */
    public function requireCustomer() {
        $this->requireLogin();
        
        if (!$this->isCustomer()) {
            $_SESSION['error_message'] = "Access denied. This page is only for customers.";
            header('Location: /index.php');
            exit;
        }
    }
    
    /**
     * Require agency
     */
    public function requireAgency() {
        $this->requireLogin();
        
        if (!$this->isAgency()) {
            $_SESSION['error_message'] = "Access denied. This page is only for agencies.";
            header('Location: /index.php');
            exit;
        }
    }
    
    /**
     * Get current user ID
     */
    public function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user type
     */
    public function getCurrentUserType() {
        return $_SESSION['user_type'] ?? null;
    }
    
    /**
     * Sanitize input
     */
    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}