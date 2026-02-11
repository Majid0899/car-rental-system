<?php
/**
 * Booking Class
 * Handles all booking-related operations
 */

require_once __DIR__ . '/Database.php';

class Booking extends Database {
    
    /**
     * Create a new booking
     * @param array $data
     * @return bool|int
     */
    public function createBooking($data) {
        try {
            $this->beginTransaction();
            
            // Insert booking
            $sql = "INSERT INTO bookings (customer_id, car_id, agency_id, start_date, end_date, rental_days, total_amount) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $this->query($sql);
            $this->bind(
                'iiissid',
                $data['customer_id'],
                $data['car_id'],
                $data['agency_id'],
                $data['start_date'],
                $data['end_date'],
                $data['rental_days'],
                $data['total_amount']
            );
            
            if (!$this->execute()) {
                $this->rollback();
                return false;
            }
            
            $bookingId = $this->lastInsertId();
            
            // Update car status to rented
            $updateSql = "UPDATE cars SET status = 'rented' WHERE id = ?";
            $this->query($updateSql);
            $this->bind('i', $data['car_id']);
            
            if (!$this->execute()) {
                $this->rollback();
                return false;
            }
            
            $this->commit();
            return $bookingId;
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("Create booking error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get bookings by customer
     * @param int $customerId
     * @return array
     */
    public function getBookingsByCustomer($customerId) {
        try {
            $sql = "SELECT b.*, c.vehicle_model, c.vehicle_number, u.agency_name 
                    FROM bookings b
                    INNER JOIN cars c ON b.car_id = c.id
                    INNER JOIN users u ON b.agency_id = u.id
                    WHERE b.customer_id = ?
                    ORDER BY b.created_at DESC";
            
            $this->query($sql);
            $this->bind('i', $customerId);
            
            return $this->resultSet();
            
        } catch (Exception $e) {
            error_log("Get customer bookings error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get bookings by agency
     * @param int $agencyId
     * @return array
     */
    public function getBookingsByAgency($agencyId) {
        try {
            $sql = "SELECT b.*, c.vehicle_model, c.vehicle_number, u.full_name as customer_name, u.phone as customer_phone, u.email as customer_email
                    FROM bookings b
                    INNER JOIN cars c ON b.car_id = c.id
                    INNER JOIN users u ON b.customer_id = u.id
                    WHERE b.agency_id = ?
                    ORDER BY b.created_at DESC";
            
            $this->query($sql);
            $this->bind('i', $agencyId);
            
            return $this->resultSet();
            
        } catch (Exception $e) {
            error_log("Get agency bookings error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get booking by ID
     * @param int $bookingId
     * @return object|null
     */
    public function getBookingById($bookingId) {
        try {
            $sql = "SELECT b.*, c.vehicle_model, c.vehicle_number, 
                    cu.full_name as customer_name, cu.phone as customer_phone,
                    ag.agency_name
                    FROM bookings b
                    INNER JOIN cars c ON b.car_id = c.id
                    INNER JOIN users cu ON b.customer_id = cu.id
                    INNER JOIN users ag ON b.agency_id = ag.id
                    WHERE b.id = ? LIMIT 1";
            
            $this->query($sql);
            $this->bind('i', $bookingId);
            
            return $this->single();
            
        } catch (Exception $e) {
            error_log("Get booking error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Cancel a booking
     * @param int $bookingId
     * @param int $customerId
     * @return bool
     */
    public function cancelBooking($bookingId, $customerId) {
        try {
            $this->beginTransaction();
            
            // Get booking details
            $booking = $this->getBookingById($bookingId);
            
            if (!$booking || $booking->customer_id != $customerId) {
                $this->rollback();
                return false;
            }
            
            // Update booking status
            $sql = "UPDATE bookings SET booking_status = 'cancelled' WHERE id = ? AND customer_id = ?";
            $this->query($sql);
            $this->bind('ii', $bookingId, $customerId);
            
            if (!$this->execute()) {
                $this->rollback();
                return false;
            }
            
            // Update car status back to available
            $updateCarSql = "UPDATE cars SET status = 'available' WHERE id = ?";
            $this->query($updateCarSql);
            $this->bind('i', $booking->car_id);
            
            if (!$this->execute()) {
                $this->rollback();
                return false;
            }
            
            $this->commit();
            return true;
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("Cancel booking error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Complete a booking
     * @param int $bookingId
     * @param int $agencyId
     * @return bool
     */
    public function completeBooking($bookingId, $agencyId) {
        try {
            $this->beginTransaction();
            
            // Get booking details
            $booking = $this->getBookingById($bookingId);
            
            if (!$booking || $booking->agency_id != $agencyId) {
                $this->rollback();
                return false;
            }
            
            // Update booking status
            $sql = "UPDATE bookings SET booking_status = 'completed' WHERE id = ? AND agency_id = ?";
            $this->query($sql);
            $this->bind('ii', $bookingId, $agencyId);
            
            if (!$this->execute()) {
                $this->rollback();
                return false;
            }
            
            // Update car status back to available
            $updateCarSql = "UPDATE cars SET status = 'available' WHERE id = ?";
            $this->query($updateCarSql);
            $this->bind('i', $booking->car_id);
            
            if (!$this->execute()) {
                $this->rollback();
                return false;
            }
            
            $this->commit();
            return true;
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("Complete booking error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if car is available for booking on given dates
     * @param int $carId
     * @param string $startDate
     * @param string $endDate
     * @return bool
     */
    public function isCarAvailable($carId, $startDate, $endDate) {
        try {
            $sql = "SELECT id FROM bookings 
                    WHERE car_id = ? 
                    AND booking_status = 'active'
                    AND (
                        (start_date <= ? AND end_date >= ?) OR
                        (start_date <= ? AND end_date >= ?) OR
                        (start_date >= ? AND end_date <= ?)
                    )
                    LIMIT 1";
            
            $this->query($sql);
            $this->bind('issssss', $carId, $startDate, $startDate, $endDate, $endDate, $startDate, $endDate);
            
            $result = $this->single();
            
            // If no overlapping booking found, car is available
            return $result === null;
            
        } catch (Exception $e) {
            error_log("Check car availability error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validate booking data
     * @param array $data
     * @return array
     */
    public function validateBookingData($data) {
        $errors = [];
        
        // Start date validation
        if (empty($data['start_date'])) {
            $errors[] = "Start date is required";
        } else {
            $startDate = new DateTime($data['start_date']);
            $today = new DateTime();
            $today->setTime(0, 0, 0);
            
            if ($startDate < $today) {
                $errors[] = "Start date cannot be in the past";
            }
        }
        
        // Rental days validation
        if (empty($data['rental_days']) || !is_numeric($data['rental_days'])) {
            $errors[] = "Number of rental days is required";
        } elseif ($data['rental_days'] < 1) {
            $errors[] = "Rental days must be at least 1";
        } elseif ($data['rental_days'] > 90) {
            $errors[] = "Rental days cannot exceed 90";
        }
        
        return $errors;
    }
}