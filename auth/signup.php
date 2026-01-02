<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | StayEasy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="https://img.icons8.com/color/96/000000/beach-house.png">
</head>
<body class="font-poppins bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-home text-2xl text-blue-600"></i>
                <a href="../index.php" class="text-2xl font-bold text-gray-800">StayEasy</a>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="../index.php" class="text-gray-700 hover:text-blue-600">Home</a>
                <a href="login.php" class="text-gray-700 hover:text-blue-600">Log in</a>
            </div>
        </div>
    </nav>

    <!-- Signup Section -->
    <section class="min-h-screen flex items-center justify-center py-12">
        <div class="w-full max-w-4xl">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Join StayEasy Today</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Create your free account and start exploring unique accommodations or listing your property for short-term rentals.</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Account Type Selection -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">Choose Your Account Type</h3>
                        
                        <div class="space-y-4">
                            <!-- Traveler Option -->
                            <div class="account-type-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition duration-300" data-account-type="traveler">
                                <div class="flex items-start">
                                    <div class="bg-blue-100 p-2 rounded-md mr-4">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Traveler</h4>
                                        <p class="text-gray-600 text-sm mt-1">I want to find and book unique accommodations for my trips.</p>
                                    </div>
                                </div>
                                <div class="mt-4 hidden" id="traveler-benefits">
                                    <ul class="space-y-2 text-sm text-gray-600">
                                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Search & book rentals</li>
                                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Save favorites</li>
                                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Leave reviews</li>
                                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Message hosts</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Host Option -->
                            <div class="account-type-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-green-400 transition duration-300" data-account-type="host">
                                <div class="flex items-start">
                                    <div class="bg-green-100 p-2 rounded-md mr-4">
                                        <i class="fas fa-home text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Host</h4>
                                        <p class="text-gray-600 text-sm mt-1">I want to list my property and earn money from short-term rentals.</p>
                                    </div>
                                </div>
                                <div class="mt-4 hidden" id="host-benefits">
                                    <ul class="space-y-2 text-sm text-gray-600">
                                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> List your property</li>
                                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Manage bookings</li>
                                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Set your own prices</li>
                                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Communicate with guests</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">Create Your Account</h3>
                        
                        <!-- Signup Form -->
                        <form id="signupForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="firstName" class="block text-gray-700 font-medium mb-2">First Name</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                                        <input type="text" id="firstName" name="firstName" required class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="John">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="lastName" class="block text-gray-700 font-medium mb-2">Last Name</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                                        <input type="text" id="lastName" name="lastName" required class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Doe">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="signupEmail" class="block text-gray-700 font-medium mb-2">Email Address</label>
                                <div class="relative">
                                    <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                                    <input type="email" id="signupEmail" name="email" required class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="you@example.com">
                                </div>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                                <div class="relative">
                                    <i class="fas fa-phone absolute left-3 top-3 text-gray-400"></i>
                                    <input type="tel" id="phone" name="phone" class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="+1 (555) 123-4567">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="signupPassword" class="block text-gray-700 font-medium mb-2">Password</label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                                        <input type="password" id="signupPassword" name="password" required class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Create a password">
                                        <button type="button" id="toggleSignupPassword" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="confirmPassword" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                                        <input type="password" id="confirmPassword" name="confirmPassword" required class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirm your password">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                                <div>
                                    <label for="city" class="block text-gray-700 font-medium mb-2">City</label>
                                    <div class="relative">
                                        <i class="fas fa-map-marker-alt absolute left-3 top-3 text-gray-400"></i>
                                        <input type="text" id="city" name="city" class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your city">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden Account Type Field -->
                            <input type="hidden" id="accountType" name="accountType" value="traveler">
                            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-md hover:bg-blue-700 transition duration-300 font-medium">
                                Create Account
                            </button>
                            
                            <div class="text-center mt-4">
                                <p class="text-gray-600">Already have an account? <a href="login.html" class="text-blue-600 font-medium hover:text-blue-800">Log in</a></p>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Platform Features -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-2">Secure Platform</h4>
                            <p class="text-gray-600 text-sm">Bank-level security for your personal information and payments.</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-headset text-green-600 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-2">24/7 Support</h4>
                            <p class="text-gray-600 text-sm">Our customer support team is available around the clock to assist you.</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-2">Verified Users</h4>
                            <p class="text-gray-600 text-sm">All hosts and guests are verified to ensure a trusted community.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <div class="flex items-center justify-center space-x-2 mb-6">
                <i class="fas fa-home text-xl text-blue-400"></i>
                <span class="text-xl font-bold">StayEasy</span>
            </div>
            <p class="text-gray-400 mb-4">Your trusted platform for short-term rentals</p>
            <div class="flex justify-center space-x-6 mb-6">
                <a href="#" class="text-gray-400 hover:text-white">Terms</a>
                <a href="#" class="text-gray-400 hover:text-white">Privacy</a>
                <a href="#" class="text-gray-400 hover:text-white">Help Center</a>
                <a href="#" class="text-gray-400 hover:text-white">Contact</a>
            </div>
            <p class="text-gray-500 text-sm">&copy; 2026 StayEasy. This is a demonstration interface for a PHP OOP project.</p>
        </div>
    </footer>

    <script src="../assets/js/app.js"></script>
</body>
</html>