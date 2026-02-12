<?php
$pageTitle = "Home";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="gradient-bg text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                <i class="fas fa-car-side"></i> Car Rental System
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">
                Rent the perfect car for your journey
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/views/customer/available-cars.php" 
                   class="px-8 py-4 bg-white text-purple-600 rounded-lg font-bold text-lg hover:shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-search mr-2"></i>Browse Available Cars
                </a>
                <a href="/views/auth/customer-register.php" 
                   class="px-8 py-4 bg-purple-800 text-white rounded-lg font-bold text-lg hover:bg-purple-900 transition transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>Register Now
                </a>
            </div>
        </div>
    </div>
    
    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">
                Why Choose Us?
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 hover-scale">
                    <div class="w-20 h-20 mx-auto mb-4 gradient-bg rounded-full flex items-center justify-center">
                        <i class="fas fa-car text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Wide Selection</h3>
                    <p class="text-gray-600">Choose from a variety of vehicles to suit your needs and budget.</p>
                </div>
                
                <div class="text-center p-6 hover-scale">
                    <div class="w-20 h-20 mx-auto mb-4 gradient-bg rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Best Prices</h3>
                    <p class="text-gray-600">Competitive rates with transparent pricing and no hidden fees.</p>
                </div>
                
                <div class="text-center p-6 hover-scale">
                    <div class="w-20 h-20 mx-auto mb-4 gradient-bg rounded-full flex items-center justify-center">
                        <i class="fas fa-headset text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">24/7 Support</h3>
                    <p class="text-gray-600">Our team is always ready to help you with any questions.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- How It Works Section -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">
                How It Works
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Register</h3>
                    <p class="text-gray-600">Create your free account as a customer or agency.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Browse</h3>
                    <p class="text-gray-600">Explore available cars and find your perfect match.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Book</h3>
                    <p class="text-gray-600">Select your dates and confirm your booking instantly.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">
                        4
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Drive</h3>
                    <p class="text-gray-600">Pick up your car and enjoy your journey!</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="py-16 gradient-bg text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Get Started?</h2>
            <p class="text-xl mb-8 opacity-90">Join thousands of satisfied customers today!</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="./views/auth/customer-register.php" 
                   class="px-8 py-4 bg-white text-purple-600 rounded-lg font-bold text-lg hover:shadow-lg transition">
                    <i class="fas fa-user mr-2"></i>Register as Customer
                </a>
                <a href="./views/auth/agency-register.php" 
                   class="px-8 py-4 bg-purple-800 text-white rounded-lg font-bold text-lg hover:bg-purple-900 transition">
                    <i class="fas fa-building mr-2"></i>Register as Agency
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>