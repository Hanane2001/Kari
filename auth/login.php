<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | StayEasy</title>
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
                <a href="signup.php" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">Sign up</a>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="min-h-screen flex items-center justify-center py-12">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h1>
                    <p class="text-gray-600">Log in to your StayEasy account to continue</p>
                </div>
                
                <!-- Login Form -->
                <form id="loginForm" class="space-y-6">
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                            <input type="email" id="email" name="email" required class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="you@example.com">
                        </div>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                            <input type="password" id="password" name="password" required class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your password">
                            <button type="button" id="togglePassword" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="flex justify-end mt-2">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Forgot password?</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500">
                        <label for="remember" class="ml-2 text-gray-700">Remember me</label>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-md hover:bg-blue-700 transition duration-300 font-medium">
                        Log In
                    </button>
                    
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" class="flex items-center justify-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-300">
                            <i class="fab fa-google text-red-500 mr-2"></i>
                            <span>Google</span>
                        </button>
                        <button type="button" class="flex items-center justify-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-300">
                            <i class="fab fa-facebook-f text-blue-600 mr-2"></i>
                            <span>Facebook</span>
                        </button>
                    </div>
                </form>
                
                <div class="mt-8 text-center">
                    <p class="text-gray-600">Don't have an account? <a href="signup.html" class="text-blue-600 font-medium hover:text-blue-800">Sign up</a></p>
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
            <p class="text-gray-500 text-sm">&copy; 2023 StayEasy. This is a demonstration interface for a PHP OOP project.</p>
        </div>
    </footer>

    <script src="../assets/js/app.js"></script>
</body>
</html>