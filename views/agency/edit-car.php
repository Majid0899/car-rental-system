<?php
$pageTitle = "Edit Car";

require_once __DIR__ . '/../../controllers/CarController.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$auth = new AuthController();
$auth->requireAgency();

$carController = new CarController();

$carId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$car = $carController->getCarById($carId);

// ✅ Redirect BEFORE output
if (!$car || $car->agency_id != $auth->getCurrentUserId()) {
    $_SESSION['error_message'] = "Car not found or you don't have permission to edit it.";
    header('Location: add-car.php');
    exit;
}

// Handle update
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $carController->updateCar($carId);
    $errors = $result['errors'];

    $car = $carController->getCarById($carId);
}

// ✅ Only now load layout
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
?>


<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="add-car.php" class="text-purple-600 hover:underline mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Manage Cars
                </a>
                <h1 class="text-4xl font-bold text-gray-800">
                    <i class="fas fa-edit text-purple-600"></i> Edit Car
                </h1>
            </div>
            
            <!-- Errors -->
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Edit Form -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <form method="POST" action="" class="space-y-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-car mr-2"></i>Vehicle Model *
                        </label>
                        <input type="text" name="vehicle_model" required 
                               value="<?php echo htmlspecialchars($_POST['vehicle_model'] ?? $car->vehicle_model); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="e.g., Toyota Camry 2023">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-hashtag mr-2"></i>Vehicle Number *
                        </label>
                        <input type="text" name="vehicle_number" required 
                               value="<?php echo htmlspecialchars($_POST['vehicle_number'] ?? $car->vehicle_number); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="e.g., ABC-1234"
                               style="text-transform: uppercase;">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-users mr-2"></i>Seating Capacity *
                            </label>
                            <input type="number" name="seating_capacity" required min="1" max="50"
                                   value="<?php echo htmlspecialchars($_POST['seating_capacity'] ?? $car->seating_capacity); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="e.g., 5">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-dollar-sign mr-2"></i>Rent Per Day ($) *
                            </label>
                            <input type="number" name="rent_per_day" required min="0" step="0.01"
                                   value="<?php echo htmlspecialchars($_POST['rent_per_day'] ?? $car->rent_per_day); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="e.g., 50.00">
                        </div>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 gradient-bg text-white py-3 rounded-lg font-medium hover:opacity-90 transition">
                            <i class="fas fa-save mr-2"></i>Update Car
                        </button>
                        
                        <a href="add-car.php" 
                           class="flex-1 bg-gray-500 text-white py-3 rounded-lg font-medium hover:bg-gray-600 transition text-center">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>