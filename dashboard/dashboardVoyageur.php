<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../classes/Reservation.php';
require_once __DIR__ . '/../classes/Favorites.php';
require_once __DIR__ . '/../classes/User.php';

$userId = $_SESSION['user_id'];
$user = User::getById($userId);
$reservations = Reservation::getByVoyageur($userId);
$favorites = new Favorites();
$favoriteList = $favorites->getFavorites($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEase - Traveler Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md py-4 px-6">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="flex items-center space-x-2">
                <i class="fas fa-suitcase text-2xl text-blue-600"></i>
                <span class="text-xl font-bold">Traveler<span class="text-blue-600">Dashboard</span></span>
            </a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="../index.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Browse Rentals</a>
                <a href="../auth/logout.php" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">My Profile</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Name</p>
                    <p class="font-medium"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Email</p>
                    <p class="font-medium"><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Phone</p>
                    <p class="font-medium"><?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Location</p>
                    <p class="font-medium"><?= htmlspecialchars($user['location']) ?></p>
                </div>
            </div>
            <a href="edit_profile.php" class="inline-block mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                <i class="fas fa-edit mr-2"></i> Edit Profile
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">My Reservations (<?= count($reservations) ?>)</h2>
            
            <?php if (empty($reservations)): ?>
                <p class="text-gray-500 text-center py-8">You haven't made any reservations yet.</p>
                <div class="text-center">
                    <a href="../index.php" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        <i class="fas fa-search mr-2"></i> Browse Available Rentals
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($reservations as $reservation): 
                        $statusColor = match($reservation['status']) {
                            'confirmed' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                            'completed' => 'bg-blue-100 text-blue-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                    ?>
                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div class="flex items-start space-x-4">
                                <img src="<?= htmlspecialchars($reservation['imageUrl']) ?>" alt="<?= htmlspecialchars($reservation['title']) ?>" class="w-20 h-20 object-cover rounded">
                                <div>
                                    <h3 class="font-bold"><?= htmlspecialchars($reservation['title']) ?></h3>
                                    <p class="text-gray-600 text-sm">Host: <?= htmlspecialchars($reservation['hote_first_name'] . ' ' . $reservation['hote_last_name']) ?></p>
                                    <p class="text-gray-600 text-sm">
                                        <?= date('M d, Y', strtotime($reservation['start_date'])) ?> - <?= date('M d, Y', strtotime($reservation['end_date'])) ?>
                                    </p>
                                    <p class="text-gray-600 text-sm">Guests: <?= $reservation['nbr_guests'] ?></p>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 text-right">
                                <p class="text-lg font-bold text-blue-600">$<?= number_format($reservation['total_price'], 2) ?></p>
                                <span class="px-3 py-1 rounded-full text-sm <?= $statusColor ?>">
                                    <?= ucfirst($reservation['status']) ?>
                                </span>
                                <div class="mt-2 space-x-2">
                                    <?php if ($reservation['status'] === 'pending' || $reservation['status'] === 'confirmed'): ?>
                                        <button class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">Cancel</button>
                                    <?php endif; ?>
                                    <?php if ($reservation['status'] === 'completed'): ?>
                                        <button class="px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600">Leave Review</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">My Favorites (<?= count($favoriteList) ?>)</h2>
            
            <?php if (empty($favoriteList)): ?>
                <p class="text-gray-500 text-center py-8">You haven't added any favorites yet.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($favoriteList as $favorite): ?>
                    <div class="border rounded-xl overflow-hidden hover:shadow-lg transition">
                        <img src="<?= htmlspecialchars($favorite['imageUrl']) ?>" alt="<?= htmlspecialchars($favorite['title']) ?>" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold mb-2"><?= htmlspecialchars($favorite['title']) ?></h3>
                            <p class="text-gray-600 text-sm mb-3">Host: <?= htmlspecialchars($favorite['hote_first_name'] . ' ' . $favorite['hote_last_name']) ?></p>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-bold">$<?= number_format($favorite['price'], 2) ?>/night</span>
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span><?= number_format($favorite['average_rating'], 1) ?></span>
                                </div>
                            </div>
                            <div class="mt-4 flex space-x-2">
                                <a href="../logement_detail.php?id=<?= $favorite['logement_id'] ?>" class="flex-1 text-center py-2 bg-blue-500 text-white rounded hover:bg-blue-600">View</a>
                                <form method="POST" action="remove_favorite.php" class="flex-1">
                                    <input type="hidden" name="logement_id" value="<?= $favorite['logement_id'] ?>">
                                    <button type="submit" class="w-full py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                        <i class="fas fa-trash mr-1"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>