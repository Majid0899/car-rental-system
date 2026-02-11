<?php
/**
 * Booking Controller
 * Handles booking-related operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Booking.php';
require_once __DIR__ . '/../classes/Car.php';
require_once __DIR__ . '/AuthController.php';

class BookingController {
    private $bookingModel;
    private $carModel;
    private $auth;
    
    public function __construct() {
        $this->bookingModel = new Booking();
        $this->carModel = new Car();
        $this->auth = new AuthController();
    }
    
    /**
     * Create booking
     */
    public function createBooking() {
        $this->auth->requireCustomer();
        
        $errors = [];
        $success = false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $carId = intval($_POST['car_id'] ?? 0);
            $rentalDays = intval($_POST['rental_days'] ?? 0);
            $startDate = $_POST['start_date'] ?? '';
            
            // Get car details
            $car = $this->carModel->getCarById($carId);
            
            if (!$car) {
                $errors[] = "Car not found";
            } elseif ($car->status !== 'available') {
                $errors[] = "Car is not available for booking";
            } else {
                // Calculate end date
                $startDateTime = new DateTime($startDate);
                $endDateTime = clone $startDateTime;
                $endDateTime->modify('+' . ($rentalDays - 1) . ' days');
                
                $data = [
                    'customer_id' => $this->auth->getCurrentUserId(),
                    'car_id' => $carId,
                    'agency_id' => $car->agency_id,
                    'start_date' => $startDate,
                    'end_date' => $endDateTime->format('Y-m-d'),
                    'rental_days' => $rentalDays,
                    'total_amount' => $car->rent_per_day * $rentalDays
                ];
                
                // Validate booking data
                $errors = $this->bookingModel->validateBookingData($data);
                
                if (empty($errors)) {
                    // Check if car is available for the dates
                    if (!$this->bookingModel->isCarAvailable($carId, $data['start_date'], $data['end_date'])) {
                        $errors[] = "Car is already booked for the selected dates";
                    } else {
                        $bookingId = $this->bookingModel->createBooking($data);
                        
                        if ($bookingId) {
                            $_SESSION['success_message'] = "Car booked successfully! Booking ID: #" . $bookingId;
                            header('Location: available-cars.php');
                            exit;
                        } else {
                            $errors[] = "Failed to create booking. Please try again.";
                        }
                    }
                }
            }
        }
        
        return ['errors' => $errors, 'success' => $success];
    }
    
    /**
     * Get customer bookings
     */
    public function getCustomerBookings() {
        $this->auth->requireCustomer();
        $customerId = $this->auth->getCurrentUserId();
        return $this->bookingModel->getBookingsByCustomer($customerId);
    }
    
    /**
     * Get agency bookings
     */
    public function getAgencyBookings() {
        $this->auth->requireAgency();
        $agencyId = $this->auth->getCurrentUserId();
        return $this->bookingModel->getBookingsByAgency($agencyId);
    }
    
    /**
     * Cancel booking
     */
    public function cancelBooking($bookingId) {
        $this->auth->requireCustomer();
        
        $customerId = $this->auth->getCurrentUserId();
        
        if ($this->bookingModel->cancelBooking($bookingId, $customerId)) {
            $_SESSION['success_message'] = "Booking cancelled successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to cancel booking.";
        }
        
        return true;
    }
    
    /**
     * Complete booking
     */
    public function completeBooking($bookingId) {
        $this->auth->requireAgency();
        
        $agencyId = $this->auth->getCurrentUserId();
        
        if ($this->bookingModel->completeBooking($bookingId, $agencyId)) {
            $_SESSION['success_message'] = "Booking marked as completed!";
        } else {
            $_SESSION['error_message'] = "Failed to complete booking.";
        }
        
        header('Location: view-bookings.php');
        exit;
    }
}