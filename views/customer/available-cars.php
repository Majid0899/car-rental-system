<?php
require_once __DIR__ . '/../../controllers/CarController.php';
require_once __DIR__ . '/../../controllers/BookingController.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$auth = new AuthController();
$carController = new CarController();
$bookingController = new BookingController();

$isLoggedIn = $auth->isLoggedIn();
$isCustomer = $auth->isCustomer();
$isAgency = $auth->isAgency();

$errors = [];

/* ===== HANDLE POST BEFORE HTML OUTPUT ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'book') {

    if (!$isLoggedIn) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: /car-rental-system/views/auth/login.php');
        exit;
    }

    if ($isAgency) {
        $errors[] = "Agencies cannot book cars. Only customers can book.";
    } else {
        $result = $bookingController->createBooking();
        $errors = $result['errors'];
    }
}

$cars = $carController->getAvailableCars();

$successMessage = $_SESSION['success_message'] ?? '';
$errorMessage = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

/* ===== LOAD HTML AFTER LOGIC ===== */
$pageTitle = "Available Cars";
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
?>


<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-car text-purple-600"></i> Available Cars for Rent
            </h1>
            <p class="text-gray-600 text-lg">Choose from our wide selection of vehicles</p>
        </div>
        
        <!-- Success/Error Messages -->
        <?php if ($successMessage): ?>
            <div class="max-w-4xl mx-auto mb-6">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($successMessage); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="max-w-4xl mx-auto mb-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($errorMessage); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="max-w-4xl mx-auto mb-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Cars Grid -->
        <?php if (empty($cars)): ?>
            <div class="text-center py-16">
                <i class="fas fa-car-side text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-semibold text-gray-600 mb-2">No Cars Available</h3>
                <p class="text-gray-500">Check back later for new listings!</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($cars as $car): ?>
                    <div class="car-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="gradient-bg text-white p-4">
                            <h3 class="text-xl font-bold"><?php echo htmlspecialchars($car->vehicle_model); ?></h3>
                            <p class="text-sm opacity-90">
                                <i class="fas fa-building mr-1"></i>
                                <?php echo htmlspecialchars($car->agency_name); ?>
                            </p>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-hashtag w-6 text-purple-600"></i>
                                    <span class="font-medium">Vehicle Number:</span>
                                    <span class="ml-2"><?php echo htmlspecialchars($car->vehicle_number); ?></span>
                                </div>
                                
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-users w-6 text-purple-600"></i>
                                    <span class="font-medium">Seating Capacity:</span>
                                    <span class="ml-2"><?php echo htmlspecialchars($car->seating_capacity); ?> Persons</span>
                                </div>
                                
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-dollar-sign w-6 text-purple-600"></i>
                                    <span class="font-medium">Rent Per Day:</span>
                                    <span class="ml-2 text-green-600 font-bold">$<?php echo number_format($car->rent_per_day, 2); ?></span>
                                </div>
                            </div>
                            
                            <?php if ($isCustomer): ?>
                                <form method="POST" action="" class="space-y-4">
                                    <input type="hidden" name="action" value="book">
                                    <input type="hidden" name="car_id" value="<?php echo $car->id; ?>">
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-calendar-day mr-1"></i>Start Date
                                        </label>
                                        <input type="date" name="start_date" required 
                                               min="<?php echo date('Y-m-d'); ?>"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-clock mr-1"></i>Number of Days
                                        </label>
                                        <select name="rental_days" required 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                            <option value="">Select days</option>
                                            <?php for ($i = 1; $i <= 30; $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?> Day<?php echo $i > 1 ? 's' : ''; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" 
                                            class="w-full gradient-bg text-white py-2 px-4 rounded-lg hover:opacity-90 transition">
                                        <i class="fas fa-check-circle mr-2"></i>Rent This Car
                                    </button>
                                </form>
                            <?php else: ?>
                                <button onclick="handleRentClick()" 
                                        class="w-full gradient-bg text-white py-3 px-4 rounded-lg hover:opacity-90 transition">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Rent
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function handleRentClick() {
    <?php if (!$isLoggedIn): ?>
        window.location.href = '/car-rental-system/views/auth/login.php';
    <?php elseif ($isAgency): ?>
        alert('Agencies cannot book cars. Only customers can book.');
    <?php endif; ?>
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>