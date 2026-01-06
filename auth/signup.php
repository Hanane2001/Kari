<?php
session_start();
require_once __DIR__ . '/../classes/User.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    require_once __DIR__ . '/../config/Database.php';
    
    $role = trim($_POST['role'] ?? 'voyageur');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $adminCode = $_POST['admin_code'] ?? '';
    $errors = [];

    if (empty($firstName) || empty($lastName)) {
        $errors[] = "First name and last name are required";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }

    if (!empty($phone) && strlen($phone) > 20) {
        $errors[] = "Phone number cannot exceed 20 characters";
    }

    if (empty($location)) {
        $errors[] = "Location is required";
    }

    if ($role === 'admin' && $adminCode !== 'admin123') {
        $errors[] = "Invalid admin access code";
    }

    if (empty($errors)) {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT user_id FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            
            if ($stmt->fetch()) {
                $errors[] = "Email already exists";
            }
        } catch (PDOException $e) {
            $errors[] = "System error. Please try again later.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: signup.php");
        exit();
    }

    $roleEnum = match($role) {
        'voyageur' => RoleUs::VOYAGEUR,
        'hote' => RoleUs::HOTE,
        'admin' => RoleUs::ADMIN,
        default => RoleUs::VOYAGEUR
    };
    
//     $id = null;
    
//     switch ($role) {
//         case 'voyageur':
//             require_once __DIR__ . '/../classes/Voyageur.php';
//             $user = new Voyageur($firstName, $lastName, $email, $phone, $location, $password);
//             $id = $user->getId();
//             break;

//         case 'hote':
//             require_once __DIR__ . '/../classes/Hote.php';
//             $user = new Hote($firstName, $lastName, $email, $phone, $location, $password);
//             $id = $user->getId();
//             break;

//         case 'admin':
//             require_once __DIR__ . '/../classes/Admin.php';
//             $user = new Admin($firstName, $lastName, $email, $phone, $location, $password);
//             $id = $user->getId();
//             break;

//         default:
//             $id = null;
//     }

//     if ($id !== null && $id !== false) {
//         $_SESSION['success_message'] = "Account created successfully! Please login.";
//         header("Location: login.php");
//         exit();
//     } else {
//         $_SESSION['errors'] = ["Failed to create account. Please try again."];
//         header("Location: signup.php");
//         exit();
//     }
// }

    $user = new User($firstName, $lastName, $email, $phone, $location, $password, $roleEnum);
    $userId = $user->signup();
    
    if ($userId !== false) {
        $_SESSION['success_message'] = "Account created successfully! Please login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['errors'] = ["Failed to create account. Please try again."];
        header("Location: signup.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEase - Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .form-bg { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card-shadow {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .input-focus:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .role-selected {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.05);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="form-bg flex items-center justify-center p-4">
        <div class="w-full max-w-4xl mx-auto">
            <div class="mb-8">
                <a href="../index.php" class="inline-flex items-center text-white hover:text-gray-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Home
                </a>
            </div>
            
            <div class="bg-white rounded-3xl overflow-hidden card-shadow">
                <div class="md:flex">
                    <div class="md:w-1/2 p-8 md:p-12">
                        <div class="text-center mb-10">
                            <a href="../index.php" class="inline-flex items-center space-x-2 mb-4">
                                <i class="fas fa-home text-3xl text-purple-600"></i>
                                <span class="text-2xl font-bold text-gray-900">Stay<span class="text-purple-600">Ease</span></span>
                            </a>
                            <h1 class="text-3xl font-bold text-gray-900">Create Your Account</h1>
                            <p class="text-gray-600 mt-2">Join our community of travelers and hosts</p>
                        </div>

                        <?php if (!empty($_SESSION['errors'])): ?>
                            <div class="mb-6 p-4 rounded-xl bg-red-100 border border-red-300 text-red-700">
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <?php foreach ($_SESSION['errors'] as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php unset($_SESSION['errors']); ?>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['success_message'])): ?>
                            <div class="mb-6 p-4 rounded-xl bg-green-100 border border-green-300 text-green-700">
                                <?= htmlspecialchars($_SESSION['success_message']) ?>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>
                        
                        <form id="signupForm" action="signup.php" method="POST" class="space-y-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address *
                                </label>
                                <input type="email" id="email" name="email" required maxlength="180" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none input-focus transition" placeholder="you@example.com" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                                <p class="text-xs text-gray-500 mt-1">Must be a valid email address</p>
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-2 text-gray-500"></i>Password *
                                </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" required minlength="6" maxlength="255" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none input-focus transition pr-12" placeholder="At least 6 characters">
                                    <button type="button" id="togglePassword" class="absolute right-4 top-3 text-gray-500">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-2 text-gray-500"></i>First Name *
                                    </label>
                                    <input type="text" id="first_name" name="first_name" required maxlength="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none input-focus transition" placeholder="John" value="<?= isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '' ?>">
                                </div>
                                
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-2 text-gray-500"></i>Last Name *
                                    </label>
                                    <input type="text" id="last_name" name="last_name" required maxlength="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none input-focus transition" placeholder="Doe" value="<?= isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '' ?>">
                                </div>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-2 text-gray-500"></i>Phone Number (Optional)
                                </label>
                                <input type="tel" id="phone" name="phone" maxlength="20" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none input-focus transition" placeholder="+1 (555) 123-4567" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                                <p class="text-xs text-gray-500 mt-1">Include country code if international</p>
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>Location *
                                </label>
                                <input type="text" id="location" name="location" required placeholder="Enter your city" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none input-focus transition" value="<?= isset($_POST['location']) ? htmlspecialchars($_POST['location']) : '' ?>"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="fas fa-user-tag mr-2 text-gray-500"></i>Select Your Role
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="roleSelection">
                                    <div class="role-option cursor-pointer border-2 border-gray-200 rounded-xl p-4 text-center transition hover:border-purple-400" data-role="voyageur">
                                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl mx-auto mb-3">
                                            <i class="fas fa-suitcase-rolling"></i>
                                        </div>
                                        <h3 class="font-bold text-gray-900">Traveler</h3>
                                        <p class="text-sm text-gray-600 mt-1">Book stays around the world</p>
                                        <div class="mt-3">
                                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Default</span>
                                        </div>
                                    </div>
                                    <div class="role-option cursor-pointer border-2 border-gray-200 rounded-xl p-4 text-center transition hover:border-purple-400" data-role="hote">
                                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl mx-auto mb-3">
                                            <i class="fas fa-home"></i>
                                        </div>
                                        <h3 class="font-bold text-gray-900">Host</h3>
                                        <p class="text-sm text-gray-600 mt-1">Rent out your property</p>
                                    </div>
                                    <div class="role-option cursor-pointer border-2 border-gray-200 rounded-xl p-4 text-center transition hover:border-purple-400 hidden" data-role="admin" id="adminOption">
                                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xl mx-auto mb-3">
                                            <i class="fas fa-user-cog"></i>
                                        </div>
                                        <h3 class="font-bold text-gray-900">Admin</h3>
                                        <p class="text-sm text-gray-600 mt-1">Platform manage</p>
                                        <div class="mt-3">
                                            <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">Invite Only</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="role" name="role" value="voyageur">
                                <div id="adminCodeContainer" class="mt-4 hidden">
                                    <label for="admin_code" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-key mr-2 text-gray-500"></i>Admin Access Code *
                                    </label>
                                    <input type="password" id="admin_code" name="admin_code" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none input-focus transition" placeholder="Enter admin invitation code">
                                    <p class="text-xs text-gray-500 mt-1">Required for admin role registration</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <input type="checkbox" id="terms" name="terms" required class="mt-1 mr-3 h-5 w-5 text-purple-600 rounded focus:ring-purple-500">
                                <label for="terms" class="text-sm text-gray-700">I agree to the <a href="#" class="text-purple-600 hover:text-purple-800 font-medium">Terms of Service</a> and <a href="#" class="text-purple-600 hover:text-purple-800 font-medium">Privacy Policy</a>. I understand that my account will be active by default.</label>
                            </div>
                            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-4 px-6 rounded-xl hover:from-purple-700 hover:to-blue-700 transition shadow-lg hover:shadow-xl">
                                <i class="fas fa-user-plus mr-2"></i> Create Account
                            </button>
                            <div class="text-center pt-4">
                                <p class="text-gray-600">
                                    Already have an account? 
                                    <a href="login.php" class="text-purple-600 font-medium hover:text-purple-800">Sign in here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                    <div class="md:w-1/2 bg-gradient-to-br from-purple-50 to-blue-50 p-8 md:p-12 flex flex-col justify-center">
                        <div class="text-center mb-10">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Why Join StayEase?</h2>
                            <p class="text-gray-700">Become part of our trusted community</p>
                        </div>
                        
                        <div class="space-y-8">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl mr-4">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Secure Platform</h3>
                                    <p class="text-gray-700">Your data is encrypted and protected with industry-standard security.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl mr-4">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Global Community</h3>
                                    <p class="text-gray-700">Connect with travelers and hosts from all around the world.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl mr-4">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Trust & Verification</h3>
                                    <p class="text-gray-700">All users are verified to ensure a safe experience for everyone.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center text-xl mr-4">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">24/7 Support</h3>
                                    <p class="text-gray-700">Our customer support team is always here to help you.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        const roleOptions = document.querySelectorAll('.role-option');
        const roleInput = document.getElementById('role');
        const adminOption = document.getElementById('adminOption');
        const adminCodeContainer = document.getElementById('adminCodeContainer');
        const adminCodeInput = document.getElementById('admin_code');
        
        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                roleOptions.forEach(opt => {
                    opt.classList.remove('role-selected', 'border-purple-600');
                    opt.classList.add('border-gray-200');
                });
                
                this.classList.remove('border-gray-200');
                this.classList.add('role-selected', 'border-purple-600');
                const selectedRole = this.getAttribute('data-role');
                roleInput.value = selectedRole;
                
                if (selectedRole === 'admin') {
                    adminCodeContainer.classList.remove('hidden');
                    adminCodeInput.required = true;
                } else {
                    adminCodeContainer.classList.add('hidden');
                    adminCodeInput.required = false;
                }
            });
        });
        
        document.querySelector('.role-option[data-role="voyageur"]').classList.add('role-selected', 'border-purple-600');
        
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'A') {
                adminOption.classList.remove('hidden');
                alert('Admin registration option unlocked!');
            }
        });
    </script>
</body>
</html>