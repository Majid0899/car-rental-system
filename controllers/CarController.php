<?php
/**
 * Car Controller
 * Handles car-related operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Car.php';
require_once __DIR__ . '/AuthController.php';

class CarController {
    private $carModel;
    private $auth;
    
    public function __construct() {
        $this->carModel = new Car();
        $this->auth = new AuthController();
    }
    
    /**
     * Add new car
     */
    public function addCar() {
        $this->auth->requireAgency();
        
        $errors = [];
        $success = false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'vehicle_model' => $this->sanitize($_POST['vehicle_model'] ?? ''),
                'vehicle_number' => strtoupper($this->sanitize($_POST['vehicle_number'] ?? '')),
                'seating_capacity' => $this->sanitize($_POST['seating_capacity'] ?? ''),
                'rent_per_day' => $this->sanitize($_POST['rent_per_day'] ?? '')
            ];
            
            // Validate data
            $errors = $this->carModel->validateCarData($data);
            
            if (empty($errors)) {
                $agencyId = $this->auth->getCurrentUserId();
                $carId = $this->carModel->addCar($data, $agencyId);
                
                if ($carId) {
                    $success = true;
                    $_SESSION['success_message'] = "Car added successfully!";
                    header('Location: add-car.php');
                    exit;
                } else {
                    $errors[] = "Failed to add car. Please try again.";
                }
            }
        }
        
        return ['errors' => $errors, 'success' => $success];
    }
    
    /**
     * Update car
     */
    public function updateCar($carId) {
        $this->auth->requireAgency();
        
        $errors = [];
        $success = false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'vehicle_model' => $this->sanitize($_POST['vehicle_model'] ?? ''),
                'vehicle_number' => strtoupper($this->sanitize($_POST['vehicle_number'] ?? '')),
                'seating_capacity' => $this->sanitize($_POST['seating_capacity'] ?? ''),
                'rent_per_day' => $this->sanitize($_POST['rent_per_day'] ?? '')
            ];
            
            // Validate data
            $errors = $this->carModel->validateCarData($data, $carId);
            
            if (empty($errors)) {
                $agencyId = $this->auth->getCurrentUserId();
                
                if ($this->carModel->updateCar($carId, $data, $agencyId)) {
                    $success = true;
                    $_SESSION['success_message'] = "Car updated successfully!";
                    header('Location: add-car.php');
                    exit;
                } else {
                    $errors[] = "Failed to update car. Please check if you own this car.";
                }
            }
        }
        
        return ['errors' => $errors, 'success' => $success];
    }
    
    /**
     * Delete car
     */
    public function deleteCar($carId) {
        $this->auth->requireAgency();
        
        $agencyId = $this->auth->getCurrentUserId();
        
        if ($this->carModel->deleteCar($carId, $agencyId)) {
            $_SESSION['success_message'] = "Car deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to delete car.";
        }
        
        header('Location: add-car.php');
        exit;
    }
    
    /**
     * Get all available cars
     */
    public function getAvailableCars() {
        return $this->carModel->getAllAvailableCars();
    }
    
    /**
     * Get agency cars
     */
    public function getAgencyCars() {
        $this->auth->requireAgency();
        $agencyId = $this->auth->getCurrentUserId();
        return $this->carModel->getCarsByAgency($agencyId);
    }
    
    /**
     * Get car by ID
     */
    public function getCarById($carId) {
        return $this->carModel->getCarById($carId);
    }
    
    /**
     * Sanitize input
     */
    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}