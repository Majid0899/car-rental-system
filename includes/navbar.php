<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userType = $_SESSION['user_type'] ?? null;
$userName = $_SESSION['user_name'] ?? '';
?>

<nav class="gradient-bg text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <i class="fas fa-car text-2xl"></i>
                <a href="/index.php" class="text-xl font-bold">Car Rental System</a>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="/views/customer/available-cars.php" class="hover:text-gray-200 transition">
                    <i class="fas fa-cars mr-1"></i> Available Cars
                </a>
                
                <?php if ($isLoggedIn): ?>
                    <?php if ($userType === 'agency'): ?>
                        <a href="/views/agency/add-car.php" class="hover:text-gray-200 transition">
                            <i class="fas fa-plus-circle mr-1"></i> Manage Cars
                        </a>
                        <a href="/views/agency/view-bookings.php" class="hover:text-gray-200 transition">
                            <i class="fas fa-calendar-check mr-1"></i> View Bookings
                        </a>
                    <?php endif; ?>
                    
                    <div class="relative group">
                        <button class="flex items-center space-x-2 hover:text-gray-200 transition">
                            <i class="fas fa-user-circle text-xl"></i>
                            <span><?php echo htmlspecialchars($userName); ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <span class="block px-4 py-2 text-sm text-gray-700 border-b">
                                    <?php echo $userType === 'agency' ? 'Agency Account' : 'Customer Account'; ?>
                                </span>
                                <a href="/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/views/auth/login.php" class="hover:text-gray-200 transition">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                    <div class="relative group">
                        <button class="hover:text-gray-200 transition">
                            <i class="fas fa-user-plus mr-1"></i> Register
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <a href="/views/auth/customer-register.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> As Customer
                                </a>
                                <a href="/views/auth/agency-register.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-building mr-2"></i> As Agency
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden pb-4">
            <a href="/views/customer/available-cars.php" class="block py-2 hover:text-gray-200">Available Cars</a>
            
            <?php if ($isLoggedIn): ?>
                <?php if ($userType === 'agency'): ?>
                    <a href="/views/agency/add-car.php" class="block py-2 hover:text-gray-200">Manage Cars</a>
                    <a href="/views/agency/view-bookings.php" class="block py-2 hover:text-gray-200">View Bookings</a>
                <?php endif; ?>
                <a href="/logout.php" class="block py-2 hover:text-gray-200">Logout</a>
            <?php else: ?>
                <a href="/views/auth/login.php" class="block py-2 hover:text-gray-200">Login</a>
                <a href="/views/auth/customer-register.php" class="block py-2 hover:text-gray-200">Register as Customer</a>
                <a href="/views/auth/agency-register.php" class="block py-2 hover:text-gray-200">Register as Agency</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>