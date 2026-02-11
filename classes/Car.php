<?php
/**
 * Car Class
 * Handles all car-related operations
 */

require_once __DIR__ . '/Database.php';

class Car extends Database {
    
    /**
     * Add a new car
     * @param array $data
     * @param int $agencyId
     * @return bool|int
     */
    public function addCar($data, $agencyId) {
        try {
            $sql = "INSERT INTO cars (agency_id, vehicle_model, vehicle_number, seating_capacity, rent_per_day, status) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $this->query($sql);
            $this->bind(
                'issids',
                $agencyId,
                $data['vehicle_model'],
                $data['vehicle_number'],
                $data['seating_capacity'],
                $data['rent_per_day'],
                $status = 'available'
            );
            
            if ($this->execute()) {
                return $this->lastInsertId();
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Add car error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update car details
     * @param int $carId
     * @param array $data
     * @param int $agencyId
     * @return bool
     */
    public function updateCar($carId, $data, $agencyId) {
        try {
            // Verify car belongs to agency
            if (!$this->carBelongsToAgency($carId, $agencyId)) {
                return false;
            }
            
            $sql = "UPDATE cars SET vehicle_model = ?, vehicle_number = ?, seating_capacity = ?, rent_per_day = ? 
                    WHERE id = ? AND agency_id = ?";
            
            $this->query($sql);
            $this->bind(
                'ssidii',
                $data['vehicle_model'],
                $data['vehicle_number'],
                $data['seating_capacity'],
                $data['rent_per_day'],
                $carId,
                $agencyId
            );
            
            return $this->execute();
            
        } catch (Exception $e) {
            error_log("Update car error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a car
     * @param int $carId
     * @param int $agencyId
     * @return bool
     */
    public function deleteCar($carId, $agencyId) {
        try {
            // Verify car belongs to agency
            if (!$this->carBelongsToAgency($carId, $agencyId)) {
                return false;
            }
            
            $sql = "DELETE FROM cars WHERE id = ? AND agency_id = ?";
            
            $this->query($sql);
            $this->bind('ii', $carId, $agencyId);
            
            return $this->execute();
            
        } catch (Exception $e) {
            error_log("Delete car error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all available cars
     * @return array
     */
    public function getAllAvailableCars() {
        try {
            $sql = "SELECT c.*, u.agency_name 
                    FROM cars c
                    LEFT JOIN users u ON c.agency_id = u.id
                    WHERE c.status = 'available'
                    ORDER BY c.created_at DESC";
            
            $this->query($sql);
            return $this->resultSet();
            
        } catch (Exception $e) {
            error_log("Get available cars error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get cars by agency
     * @param int $agencyId
     * @return array
     */
    public function getCarsByAgency($agencyId) {
        try {
            $sql = "SELECT * FROM cars WHERE agency_id = ? ORDER BY created_at DESC";
            
            $this->query($sql);
            $this->bind('i', $agencyId);
            
            return $this->resultSet();
            
        } catch (Exception $e) {
            error_log("Get agency cars error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get car by ID
     * @param int $carId
     * @return object|null
     */
    public function getCarById($carId) {
        try {
            $sql = "SELECT c.*, u.agency_name 
                    FROM cars c
                    LEFT JOIN users u ON c.agency_id = u.id
                    WHERE c.id = ? LIMIT 1";
            
            $this->query($sql);
            $this->bind('i', $carId);
            
            return $this->single();
            
        } catch (Exception $e) {
            error_log("Get car error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update car status
     * @param int $carId
     * @param string $status
     * @return bool
     */
    public function updateCarStatus($carId, $status) {
        try {
            $sql = "UPDATE cars SET status = ? WHERE id = ?";
            
            $this->query($sql);
            $this->bind('si', $status, $carId);
            
            return $this->execute();
            
        } catch (Exception $e) {
            error_log("Update car status error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if vehicle number exists
     * @param string $vehicleNumber
     * @param int|null $excludeCarId
     * @return bool
     */
    public function vehicleNumberExists($vehicleNumber, $excludeCarId = null) {
        try {
            if ($excludeCarId) {
                $sql = "SELECT id FROM cars WHERE vehicle_number = ? AND id != ? LIMIT 1";
                $this->query($sql);
                $this->bind('si', $vehicleNumber, $excludeCarId);
            } else {
                $sql = "SELECT id FROM cars WHERE vehicle_number = ? LIMIT 1";
                $this->query($sql);
                $this->bind('s', $vehicleNumber);
            }
            
            $result = $this->single();
            return $result !== null;
            
        } catch (Exception $e) {
            error_log("Vehicle number check error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if car belongs to agency
     * @param int $carId
     * @param int $agencyId
     * @return bool
     */
    public function carBelongsToAgency($carId, $agencyId) {
        try {
            $sql = "SELECT id FROM cars WHERE id = ? AND agency_id = ? LIMIT 1";
            
            $this->query($sql);
            $this->bind('ii', $carId, $agencyId);
            
            $result = $this->single();
            return $result !== null;
            
        } catch (Exception $e) {
            error_log("Car ownership check error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validate car data
     * @param array $data
     * @param int|null $carId
     * @return array
     */
    public function validateCarData($data, $carId = null) {
        $errors = [];
        
        // Vehicle model validation
        if (empty($data['vehicle_model'])) {
            $errors[] = "Vehicle model is required";
        }
        
        // Vehicle number validation
        if (empty($data['vehicle_number'])) {
            $errors[] = "Vehicle number is required";
        } elseif ($this->vehicleNumberExists($data['vehicle_number'], $carId)) {
            $errors[] = "Vehicle number already exists";
        }
        
        // Seating capacity validation
        if (empty($data['seating_capacity'])) {
            $errors[] = "Seating capacity is required";
        } elseif (!is_numeric($data['seating_capacity']) || $data['seating_capacity'] < 1) {
            $errors[] = "Seating capacity must be a positive number";
        }
        
        // Rent per day validation
        if (empty($data['rent_per_day'])) {
            $errors[] = "Rent per day is required";
        } elseif (!is_numeric($data['rent_per_day']) || $data['rent_per_day'] <= 0) {
            $errors[] = "Rent per day must be a positive number";
        }
        
        return $errors;
    }
}