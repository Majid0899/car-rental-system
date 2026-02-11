<?php
require_once __DIR__ . '/../../controllers/AuthController.php';

$auth = new AuthController();
$result = $auth->registerCustomer(); // BEFORE HTML
$errors = $result['errors'];

$pageTitle = "Customer Registration";

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
?>


<div class="min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <i class="fas fa-user-plus text-5xl text-purple-600 mb-4"></i>
                <h1 class="text-3xl font-bold text-gray-800">Customer Registration</h1>
                <p class="text-gray-600 mt-2">Join us to rent your perfect car</p>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-user mr-2"></i>Full Name *
                    </label>
                    <input type="text" name="full_name" required 
                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Enter your full name">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email *
                    </label>
                    <input type="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Enter your email">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-phone mr-2"></i>Phone Number *
                    </label>
                    <input type="tel" name="phone" required 
                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Enter your phone number">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>Address
                    </label>
                    <textarea name="address" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                              placeholder="Enter your address"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-lock mr-2"></i>Password *
                    </label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Enter password (min 6 characters)">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-lock mr-2"></i>Confirm Password *
                    </label>
                    <input type="password" name="confirm_password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Re-enter your password">
                </div>
                
                <button type="submit" 
                        class="w-full gradient-bg text-white py-3 rounded-lg font-medium hover:opacity-90 transition">
                    <i class="fas fa-user-check mr-2"></i>Register as Customer
                </button>
            </form>
            
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    Already have an account? 
                    <a href="login.php" class="text-purple-600 hover:underline font-medium">Login here</a>
                </p>
                <p class="text-gray-600 mt-2">
                    Want to register as agency? 
                    <a href="agency-register.php" class="text-purple-600 hover:underline font-medium">Click here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
