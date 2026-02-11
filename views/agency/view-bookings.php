<?php
$pageTitle = "View Bookings";

require_once __DIR__ . '/../../controllers/BookingController.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$auth = new AuthController();
$auth->requireAgency(); // ✅ redirect safe here

$bookingController = new BookingController();

// ✅ Handle booking completion BEFORE output
if (isset($_GET['complete'])) {
    $bookingController->completeBooking(intval($_GET['complete']));
}

$bookings = $bookingController->getAgencyBookings();

$successMessage = $_SESSION['success_message'] ?? '';
$errorMessage = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// ✅ Only now load UI
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
?>


<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-calendar-check text-purple-600"></i> Customer Bookings
            </h1>
            <p class="text-gray-600">View and manage all bookings for your cars</p>
        </div>
        
        <!-- Success/Error Messages -->
        <?php if ($successMessage): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>
        
        <!-- Bookings List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if (empty($bookings)): ?>
                <div class="text-center py-16">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-600 mb-2">No Bookings Yet</h3>
                    <p class="text-gray-500">Bookings will appear here when customers rent your cars.</p>
                </div>
            <?php else: ?>
                <!-- Stats -->
                <div class="gradient-bg text-white p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php
                        $activeBookings = array_filter($bookings, fn($b) => $b->booking_status === 'active');
                        $completedBookings = array_filter($bookings, fn($b) => $b->booking_status === 'completed');
                        $totalRevenue = array_sum(array_column($bookings, 'total_amount'));
                        ?>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold"><?php echo count($activeBookings); ?></div>
                            <div class="text-sm opacity-90">Active Bookings</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold"><?php echo count($completedBookings); ?></div>
                            <div class="text-sm opacity-90">Completed Bookings</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold">$<?php echo number_format($totalRevenue, 2); ?></div>
                            <div class="text-sm opacity-90">Total Revenue</div>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Booking ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Car Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Rental Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($bookings as $booking): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono font-semibold text-purple-600">#<?php echo $booking->id; ?></span>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($booking->customer_name); ?></div>
                                            <div class="text-gray-500">
                                                <i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($booking->customer_email); ?>
                                            </div>
                                            <div class="text-gray-500">
                                                <i class="fas fa-phone mr-1"></i><?php echo htmlspecialchars($booking->customer_phone); ?>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($booking->vehicle_model); ?></div>
                                            <div class="text-gray-500"><?php echo htmlspecialchars($booking->vehicle_number); ?></div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div class="text-gray-900">
                                                <i class="fas fa-calendar-day mr-1"></i>
                                                <?php echo date('M d, Y', strtotime($booking->start_date)); ?>
                                            </div>
                                            <div class="text-gray-900">
                                                <i class="fas fa-calendar-check mr-1"></i>
                                                <?php echo date('M d, Y', strtotime($booking->end_date)); ?>
                                            </div>
                                            <div class="text-gray-500">
                                                <?php echo $booking->rental_days; ?> day<?php echo $booking->rental_days > 1 ? 's' : ''; ?>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-green-600">
                                            $<?php echo number_format($booking->total_amount, 2); ?>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($booking->booking_status === 'active'): ?>
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        <?php elseif ($booking->booking_status === 'completed'): ?>
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Completed
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <?php if ($booking->booking_status === 'active'): ?>
                                            <a href="?complete=<?php echo $booking->id; ?>" 
                                               onclick="return confirm('Mark this booking as completed?');"
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-check-circle mr-1"></i>Complete
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400">No actions</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>