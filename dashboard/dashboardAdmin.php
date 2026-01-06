<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../classes/AdminStats.php';
require_once __DIR__ . '/../classes/User.php';

$adminStats = new AdminStats();
$stats = $adminStats->getDashboardStats();
$allUsers = $adminStats->getAllUsers();
$allLogements = $adminStats->getAllLogements();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_user'])) {
        $adminStats->toggleUserStatus((int)$_POST['user_id']);
        header("Location: dashboardAdmin.php");
        exit();
    }
    
    if (isset($_POST['toggle_logement'])) {
        $adminStats->toggleLogementStatus((int)$_POST['logement_id']);
        header("Location: dashboardAdmin.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEase - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md py-4 px-6">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="flex items-center space-x-2">
                <i class="fas fa-home text-2xl text-purple-600"></i>
                <span class="text-xl font-bold">Admin<span class="text-purple-600">Dashboard</span></span>
            </a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="../auth/logout.php" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-80">Total Users</p>
                        <h3 class="text-3xl font-bold"><?= $stats['total_users'] ?? 0 ?></h3>
                    </div>
                    <i class="fas fa-users text-3xl opacity-70"></i>
                </div>
            </div>
            
            <div class="stat-card text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-80">Total Rentals</p>
                        <h3 class="text-3xl font-bold"><?= $stats['total_logements'] ?? 0 ?></h3>
                    </div>
                    <i class="fas fa-home text-3xl opacity-70"></i>
                </div>
            </div>
            
            <div class="stat-card text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-80">Total Bookings</p>
                        <h3 class="text-3xl font-bold"><?= $stats['total_reservations'] ?? 0 ?></h3>
                    </div>
                    <i class="fas fa-calendar-check text-3xl opacity-70"></i>
                </div>
            </div>
            
            <div class="stat-card text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-80">Total Revenue</p>
                        <h3 class="text-3xl font-bold">$<?= number_format($stats['total_revenue'] ?? 0, 2) ?></h3>
                    </div>
                    <i class="fas fa-dollar-sign text-3xl opacity-70"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Top 5 Most Profitable Rentals</h2>
                <div class="space-y-4">
                    <?php foreach (array_slice($stats['top_logements'] ?? [], 0, 5) as $logement): ?>
                    <div class="flex items-center justify-between p-3 border rounded-lg">
                        <div>
                            <h4 class="font-medium"><?= htmlspecialchars($logement['title']) ?></h4>
                            <p class="text-sm text-gray-600">$<?= number_format($logement['total_income'], 2) ?> revenue</p>
                        </div>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm">
                            <?= $logement['reservation_count'] ?> bookings
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Recent Registered Users</h2>
                <div class="space-y-3">
                    <?php foreach (array_slice($stats['recent_users'] ?? [], 0, 5) as $user): ?>
                    <div class="flex items-center justify-between p-3 border rounded-lg">
                        <div>
                            <h4 class="font-medium"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h4>
                            <p class="text-sm text-gray-600"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                        <span class="px-2 py-1 <?= $user['role'] === 'admin' ? 'bg-red-100 text-red-700' : ($user['role'] === 'hote' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') ?> rounded text-xs">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mt-8">
            <h2 class="text-xl font-bold mb-4">User Management</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-3">Name</th>
                            <th class="text-left p-3">Email</th>
                            <th class="text-left p-3">Role</th>
                            <th class="text-left p-3">Status</th>
                            <th class="text-left p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsers as $user): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs <?= $user['role'] === 'admin' ? 'bg-red-100 text-red-700' : ($user['role'] === 'hote' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs <?= $user['is_active'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="p-3">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                    <button type="submit" name="toggle_user" class="px-3 py-1 <?= $user['is_active'] ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' ?> text-white rounded text-sm">
                                        <?= $user['is_active'] ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mt-8">
            <h2 class="text-xl font-bold mb-4">Rental Management</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-3">Title</th>
                            <th class="text-left p-3">Host</th>
                            <th class="text-left p-3">Price</th>
                            <th class="text-left p-3">Status</th>
                            <th class="text-left p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allLogements as $logement): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium"><?= htmlspecialchars($logement['title']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($logement['first_name'] . ' ' . $logement['last_name']) ?></td>
                            <td class="p-3">$<?= number_format($logement['price'], 2) ?>/night</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs <?= $logement['is_active'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= $logement['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="p-3">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="logement_id" value="<?= $logement['logement_id'] ?>">
                                    <button type="submit" name="toggle_logement" class="px-3 py-1 <?= $logement['is_active'] ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' ?> text-white rounded text-sm">
                                        <?= $logement['is_active'] ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>