<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/User.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required";
    }

    if (empty($errors)) {
        $user = User::login($email, $password);
        
        if ($user) {
            $isActive = User::isActive($user['user_id']);
            if (!$isActive) {
                $errors[] = "Your account is not active. Please contact support.";
            } else {
                switch ($user['role']) {
                    case 'admin':
                        header("Location: ../dashboard/dashboardAdmin.php");
                        break;
                    case 'hote':
                        header("Location: ../dashboard/dashboardHote.php");
                        break;
                    default:
                        header("Location: ../dashboard/dashboardVoyageur.php");
                }
                exit();
            }
        } else {
            $errors[] = "Invalid email or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEase - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .login-bg { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="login-bg">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <div class="mb-8">
                <a href="../index.php" class="inline-flex items-center text-white hover:text-gray-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Home
                </a>
            </div>
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <div class="text-center mb-8">
                    <a href="../index.php" class="inline-flex items-center space-x-2 mb-4">
                        <i class="fas fa-home text-3xl text-purple-600"></i>
                        <span class="text-2xl font-bold text-gray-900">Stay<span class="text-purple-600">Ease</span></span>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Welcome Back</h1>
                    <p class="text-gray-600 mt-2">Sign in to your account</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="mb-6 p-4 rounded-xl bg-red-100 border border-red-300 text-red-700">
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address
                        </label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition" placeholder="you@example.com">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-500"></i>Password
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition pr-12" placeholder="Enter your password">
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-3 text-gray-500">
                                <i id="passwordIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-purple-600 rounded">
                            <label for="remember" class="ml-2 text-sm text-gray-700">Remember me</label>
                        </div>
                        <a href="forgot-password.php" class="text-sm text-purple-600 hover:text-purple-800">Forgot password?</a>
                    </div>
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-4 px-6 rounded-xl hover:from-purple-700 hover:to-blue-700 transition shadow-lg hover:shadow-xl">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                    </button>
                    
                    <div class="text-center pt-4">
                        <p class="text-gray-600">
                            Don't have an account? 
                            <a href="signup.php" class="text-purple-600 font-medium hover:text-purple-800">Sign up here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>