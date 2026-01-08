<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

require_once __DIR__ . '/classes/Favorites.php';

$favorites = new Favorites();
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['logement_id'])) {
        $logementId = (int)$_POST['logement_id'];
        
        if ($_POST['action'] === 'add') {
            $favorites->addFavorite($userId, $logementId);
        } elseif ($_POST['action'] === 'remove') {
            $favorites->removeFavorite($userId, $logementId);
        }
        header("Location: favorites.php");
        exit();
    }
}

$favoriteList = $favorites->getFavorites($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites - StayEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md py-4 px-6">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="flex items-center space-x-2">
                <i class="fas fa-home text-2xl text-red-500"></i>
                <span class="text-xl font-bold">Stay<span class="text-red-500">Ease</span></span>
            </a>
            <div class="flex items-center space-x-4">
                <a href="dashboard/dashboardVoyageur.php" class="text-gray-700 hover:text-red-500">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                </a>
                <a href="index.php" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Browse Rentals</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Favorite Properties</h1>
            <p class="text-gray-600 mt-2">Save properties you love for your next trip</p>
        </div>

        <div class="mb-6">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-red-600"><?= count($favoriteList) ?></span>
                        <span class="text-gray-600 ml-2">saved properties</span>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="clearAllFavorites()" class="px-4 py-2 border border-red-500 text-red-500 rounded hover:bg-red-50">
                            Clear All
                        </button>
                        <a href="index.php" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            <i class="fas fa-search mr-2"></i> Find More
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (empty($favoriteList)): ?>
            <div class="text-center py-12 bg-white rounded-xl shadow">
                <i class="fas fa-heart text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">No favorites yet</h3>
                <p class="text-gray-600 mb-6">Start exploring and save properties you love!</p>
                <a href="index.php" class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    <i class="fas fa-search mr-2"></i> Browse Rentals
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($favoriteList as $favorite): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="relative">
                        <a href="logement_detail.php?id=<?= $favorite['logement_id'] ?>">
                            <img src="<?= htmlspecialchars($favorite['imageUrl']) ?>" 
                                 alt="<?= htmlspecialchars($favorite['title']) ?>" 
                                 class="w-full h-48 object-cover">
                        </a>
                        <form method="POST" class="absolute top-4 right-4">
                            <input type="hidden" name="logement_id" value="<?= $favorite['logement_id'] ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="text-2xl text-red-500 hover:text-red-600">
                                <i class="fas fa-heart"></i>
                            </button>
                        </form>
                        <span class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-medium shadow">
                            $<?= number_format($favorite['price'], 2) ?>/night
                        </span>
                    </div>
                    
                    <div class="p-5">
                        <a href="logement_detail.php?id=<?= $favorite['logement_id'] ?>" class="block">
                            <h3 class="font-bold text-lg mb-1 hover:text-red-500 transition">
                                <?= htmlspecialchars($favorite['title']) ?>
                            </h3>
                        </a>
                        
                        <p class="text-gray-600 mb-3 text-sm">
                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                            <?= htmlspecialchars($favorite['location']) ?>
                        </p>
                        
                        <div class="flex items-center mb-4">
                            <?php if ($favorite['average_rating'] > 0): ?>
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                <span class="font-medium"><?= number_format($favorite['average_rating'], 1) ?></span>
                                <span class="text-gray-500 text-sm ml-1">rating</span>
                            <?php else: ?>
                                <span class="text-gray-500 text-sm">No reviews yet</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600 mb-4">
                            <div class="flex items-center mr-4">
                                <i class="fas fa-user-friends mr-2"></i>
                                <span><?= $favorite['capacity'] ?> guests</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user-tie mr-2"></i>
                                <span><?= htmlspecialchars($favorite['hote_first_name'] . ' ' . $favorite['hote_last_name']) ?></span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="logement_detail.php?id=<?= $favorite['logement_id'] ?>" 
                               class="flex-1 text-center py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                View Details
                            </a>
                            <form method="POST" class="flex-1">
                                <input type="hidden" name="logement_id" value="<?= $favorite['logement_id'] ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit" class="w-full py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-8 bg-white rounded-xl shadow p-6">
                <h3 class="font-bold mb-4">Filter Favorites</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Price Range</label>
                        <select class="w-full p-2 border rounded">
                            <option>Any price</option>
                            <option>Under $100</option>
                            <option>$100 - $200</option>
                            <option>$200 - $500</option>
                            <option>Over $500</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Property Type</label>
                        <select class="w-full p-2 border rounded">
                            <option>Any type</option>
                            <option>Apartment</option>
                            <option>House</option>
                            <option>Villa</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Minimum Rating</label>
                        <select class="w-full p-2 border rounded">
                            <option>Any rating</option>
                            <option>4.5+ stars</option>
                            <option>4.0+ stars</option>
                            <option>3.5+ stars</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Sort By</label>
                        <select class="w-full p-2 border rounded">
                            <option>Recently added</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Highest rated</option>
                        </select>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function clearAllFavorites() {
            if (confirm('Are you sure you want to remove all favorites?')) {
                alert('Clear all functionality would be implemented here');
            }
        }
        
        document.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', function() {
                console.log('Filter changed:', this.value);
            });
        });
    </script>
</body>
</html>
