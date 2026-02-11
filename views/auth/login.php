<?php
require_once __DIR__ . '/../../controllers/AuthController.php';

$auth = new AuthController();
$result = $auth->login(); // ✅ run BEFORE HTML
$errors = $result['errors'];

$successMessage = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

$pageTitle = "Login";

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
?>


<div class="min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <i class="fas fa-sign-in-alt text-5xl text-purple-600 mb-4"></i>
                <h1 class="text-3xl font-bold text-gray-800">Welcome Back</h1>
                <p class="text-gray-600 mt-2">Login to your account</p>
            </div>
            
            <?php if ($successMessage): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($successMessage); ?>
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
            
            <form method="POST" action="" class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input type="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Enter your email">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Enter your password">
                </div>
                
                <button type="submit" 
                        class="w-full gradient-bg text-white py-3 rounded-lg font-medium hover:opacity-90 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>
            
            <div class="text-center mt-6">
                <p class="text-gray-600 mb-3">Don't have an account?</p>
                <div class="space-y-2">
                    <a href="customer-register.php" 
                       class="block w-full px-4 py-2 border-2 border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition">
                        <i class="fas fa-user mr-2"></i>Register as Customer
                    </a>
                    <a href="agency-register.php" 
                       class="block w-full px-4 py-2 border-2 border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition">
                        <i class="fas fa-building mr-2"></i>Register as Agency
                    </a>
                </div>
            </div>
            
            
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>