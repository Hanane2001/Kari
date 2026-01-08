<?php
session_start();
require_once '../classes/User.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$user = User::getById($userId);

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        try {
            $data = ['first_name' => $_POST['first_name'],'last_name' => $_POST['last_name'],'phone' => $_POST['phone'],'location' => $_POST['location']];

            if (User::updateProfile($userId, $data)) {
                $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
                $_SESSION['user_location'] = $data['location'];
                $_SESSION['success'] = "Profile updated successfully!";

                if ($_SESSION['user_role'] === 'hote') {
                    header("Location: dashboardHote.php");
                } elseif ($_SESSION['user_role'] === 'admin') {
                    header("Location: dashboardAdmin.php");
                } else {
                    header("Location: dashboardVoyageur.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Failed to update profile.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEase - Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-2xl">
            <div class="mb-8">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'hote'): ?>
                        <a href="dashboardHote.php" class="inline-flex items-center font-medium text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Host Dashboard
                        </a>
                    <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="dashboardAdmin.php" class="inline-flex items-center font-medium text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Admin Dashboard
                        </a>
                    <?php else: ?>
                        <a href="dashboardVoyageur.php" class="inline-flex items-center font-medium text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Traveler Dashboard
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Profile</h1>
                    <p class="text-gray-600">Update your personal information</p>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">First Name *</label>
                            <input type="text" name="first_name" required 
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Last Name *</label>
                            <input type="text" name="last_name" required 
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Email</label>
                        <input type="email" disabled 
                               class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50"
                               value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                        <p class="text-sm text-gray-500 mt-1">Email cannot be changed</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Phone Number *</label>
                        <input type="tel" name="phone" required 
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Location *</label>
                        <input type="text" name="location" required 
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               value="<?= htmlspecialchars($user['location'] ?? '') ?>">
                        <p class="text-sm text-gray-500 mt-1">City, Country</p>
                    </div>

                    <div class="pt-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-user-tag mr-1"></i>
                                    Role: <span class="font-medium capitalize"><?= htmlspecialchars($user['role'] ?? 'traveler') ?></span>
                                </p>
                            </div>
                            <button type="submit" name="update_profile" 
                                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-10 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Member Since</p>
                            <p class="font-medium">
                                <?php 
                                if (isset($user['created_at'])) {
                                    echo date('F d, Y', strtotime($user['created_at']));
                                } else {
                                    echo 'Not available';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Account Status</p>
                            <p class="font-medium">
                                <span class="px-2 py-1 rounded-full text-xs <?= $user['is_active'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-shield-alt text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <p class="font-medium text-blue-800">Security Information</p>
                            <p class="text-sm text-blue-600 mt-1">
                                For security reasons, passwords cannot be changed from this page. 
                                If you need to change your password, please contact support.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>