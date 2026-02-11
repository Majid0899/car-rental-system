<?php
$pageTitle = "Manage Cars";

require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/CarController.php';

$auth = new AuthController();
$auth->requireAgency();

$carController = new CarController();


// ✅ HANDLE DELETE HERE (before output)
if (isset($_GET['delete'])) {
    $carController->deleteCar(intval($_GET['delete']));
}


// Handle add car
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['car_id'])) {
    $result = $carController->addCar();
    $errors = $result['errors'];
}

// Now include layout
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$cars = $carController->getAgencyCars();


$successMessage = $_SESSION['success_message'] ?? '';
$errorMessage = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-car text-purple-600"></i> Manage Your Cars
            </h1>
            <p class="text-gray-600">Add new cars and manage your fleet</p>
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
        
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Add Car Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-plus-circle text-purple-600 mr-2"></i>Add New Car
                    </h2>
                    
                    <form method="POST" action="" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-car mr-1"></i>Vehicle Model *
                            </label>
                            <input type="text" name="vehicle_model" required 
                                   value="<?php echo htmlspecialchars($_POST['vehicle_model'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="e.g., Toyota Camry 2023">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-hashtag mr-1"></i>Vehicle Number *
                            </label>
                            <input type="text" name="vehicle_number" required 
                                   value="<?php echo htmlspecialchars($_POST['vehicle_number'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="e.g., ABC-1234"
                                   style="text-transform: uppercase;">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-users mr-1"></i>Seating Capacity *
                            </label>
                            <input type="number" name="seating_capacity" required min="1" max="50"
                                   value="<?php echo htmlspecialchars($_POST['seating_capacity'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="e.g., 5">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-dollar-sign mr-1"></i>Rent Per Day ($) *
                            </label>
                            <input type="number" name="rent_per_day" required min="0" step="0.01"
                                   value="<?php echo htmlspecialchars($_POST['rent_per_day'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="e.g., 50.00">
                        </div>
                        
                        <button type="submit" 
                                class="w-full gradient-bg text-white py-3 rounded-lg font-medium hover:opacity-90 transition">
                            <i class="fas fa-plus mr-2"></i>Add Car
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Cars List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-list text-purple-600 mr-2"></i>Your Cars (<?php echo count($cars); ?>)
                    </h2>
                    
                    <?php if (empty($cars)): ?>
                        <div class="text-center py-12">
                            <i class="fas fa-car-side text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">No cars added yet. Add your first car!</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($cars as $car): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-800 mb-2">
                                                <?php echo htmlspecialchars($car->vehicle_model); ?>
                                            </h3>
                                            
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                                                <div class="text-gray-600">
                                                    <i class="fas fa-hashtag text-purple-600 mr-1"></i>
                                                    <strong>Number:</strong> <?php echo htmlspecialchars($car->vehicle_number); ?>
                                                </div>
                                                
                                                <div class="text-gray-600">
                                                    <i class="fas fa-users text-purple-600 mr-1"></i>
                                                    <strong>Seats:</strong> <?php echo htmlspecialchars($car->seating_capacity); ?>
                                                </div>
                                                
                                                <div class="text-gray-600">
                                                    <i class="fas fa-dollar-sign text-purple-600 mr-1"></i>
                                                    <strong>Rent/Day:</strong> $<?php echo number_format($car->rent_per_day, 2); ?>
                                                </div>
                                                
                                                <div class="text-gray-600">
                                                    <i class="fas fa-info-circle text-purple-600 mr-1"></i>
                                                    <strong>Status:</strong> 
                                                    <span class="<?php echo $car->status === 'available' ? 'text-green-600' : 'text-orange-600'; ?>">
                                                        <?php echo ucfirst($car->status); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex space-x-2 mt-4 md:mt-0">
                                            <a href="edit-car.php?id=<?php echo $car->id; ?>" 
                                               class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            
                                            <a href="?delete=<?php echo $car->id; ?>" 
                                               onclick="return confirm('Are you sure you want to delete this car?');"
                                               class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Handle delete
if (isset($_GET['delete'])) {
    $carController->deleteCar(intval($_GET['delete']));
}
?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>