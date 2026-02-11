<?php
/**
 * User Class
 * Handles all user-related operations (customers and agencies)
 */

require_once __DIR__ . '/Database.php';

class User extends Database {
    
    /**
     * Register a new customer
     * @param array $data
     * @return bool|int
     */
    public function registerCustomer($data) {
        try {
            $sql = "INSERT INTO users (user_type, email, password, full_name, phone, address) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $this->query($sql);
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $this->bind(
                'ssssss',
                $userType = 'customer',
                $data['email'],
                $hashedPassword,
                $data['full_name'],
                $data['phone'],
                $data['address']
            );
            
            if ($this->execute()) {
                return $this->lastInsertId();
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Customer registration error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Register a new car rental agency
     * @param array $data
     * @return bool|int
     */
    public function registerAgency($data) {
        try {
            $sql = "INSERT INTO users (user_type, email, password, full_name, phone, address, agency_name, license_number) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $this->query($sql);
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $this->bind(
                'ssssssss',
                $userType = 'agency',
                $data['email'],
                $hashedPassword,
                $data['full_name'],
                $data['phone'],
                $data['address'],
                $data['agency_name'],
                $data['license_number']
            );
            
            if ($this->execute()) {
                return $this->lastInsertId();
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Agency registration error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Login user (both customer and agency)
     * @param string $email
     * @param string $password
     * @return object|bool
     */
    public function login($email, $password) {
        try {
            $sql = "SELECT * FROM users WHERE email = ? AND status = 'active' LIMIT 1";
            
            $this->query($sql);
            $this->bind('s', $email);
            
            $user = $this->single();
            
            if ($user && password_verify($password, $user->password)) {
                // Remove password from user object
                unset($user->password);
                return $user;
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if email already exists
     * @param string $email
     * @return bool
     */
    public function emailExists($email) {
        try {
            $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
            
            $this->query($sql);
            $this->bind('s', $email);
            
            $result = $this->single();
            
            return $result !== null;
            
        } catch (Exception $e) {
            error_log("Email check error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user by ID
     * @param int $userId
     * @return object|null
     */
    public function getUserById($userId) {
        try {
            $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
            
            $this->query($sql);
            $this->bind('i', $userId);
            
            $user = $this->single();
            
            if ($user) {
                unset($user->password);
            }
            
            return $user;
            
        } catch (Exception $e) {
            error_log("Get user error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update user profile
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updateProfile($userId, $data) {
        try {
            $sql = "UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?";
            
            $this->query($sql);
            $this->bind(
                'sssi',
                $data['full_name'],
                $data['phone'],
                $data['address'],
                $userId
            );
            
            return $this->execute();
            
        } catch (Exception $e) {
            error_log("Update profile error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validate user input
     * @param array $data
     * @param string $type (customer or agency)
     * @return array
     */
    public function validateUserData($data, $type = 'customer') {
        $errors = [];
        
        // Email validation
        if (empty($data['email'])) {
            $errors[] = "Email is required";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } elseif ($this->emailExists($data['email'])) {
            $errors[] = "Email already registered";
        }
        
        // Password validation
        if (empty($data['password'])) {
            $errors[] = "Password is required";
        } elseif (strlen($data['password']) < 6) {
            $errors[] = "Password must be at least 6 characters";
        }
        
        // Confirm password
        if (isset($data['confirm_password']) && $data['password'] !== $data['confirm_password']) {
            $errors[] = "Passwords do not match";
        }
        
        // Full name validation
        if (empty($data['full_name'])) {
            $errors[] = "Full name is required";
        }
        
        // Phone validation
        if (empty($data['phone'])) {
            $errors[] = "Phone number is required";
        }
        
        // Agency-specific validation
        if ($type === 'agency') {
            if (empty($data['agency_name'])) {
                $errors[] = "Agency name is required";
            }
            
            if (empty($data['license_number'])) {
                $errors[] = "License number is required";
            }
        }
        
        return $errors;
    }
}