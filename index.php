<?php
session_start();
require_once __DIR__ . '/classes/Logement.php';
require_once __DIR__ . '/classes/Favorites.php';

$searchResults = [];
$favorites = null;

if (isset($_SESSION['user_id'])) {
    $favorites = new Favorites();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $city = $_GET['city'] ?? null;
    $minPrice = !empty($_GET['min_price']) ? (float)$_GET['min_price'] : null;
    $maxPrice = !empty($_GET['max_price']) ? (float)$_GET['max_price'] : null;
    $startDate = !empty($_GET['start_date']) ? new DateTime($_GET['start_date']) : null;
    $endDate = !empty($_GET['end_date']) ? new DateTime($_GET['end_date']) : null;
    $guests = !empty($_GET['guests']) ? (int)$_GET['guests'] : null;
    
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 12;
    $offset = ($page - 1) * $limit;
    
    $searchResults = Logement::getAllAvailable($city, $minPrice, $maxPrice, $startDate, $endDate, $guests, $limit, $offset);

    $totalResults = count(Logement::getAllAvailable($city, $minPrice, $maxPrice, $startDate, $endDate, $guests, 1000, 0));
    $totalPages = ceil($totalResults / $limit);
} else {
    $searchResults = Logement::getAllAvailable(null, null, null, null, null, null, 12, 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEase - Vacation Rentals & Short-Term Stays</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-bg { background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1518780664697-55e3ad937233?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'); }
        .btn-primary { background-color: #FF385C; }
        .btn-primary:hover { background-color: #E31C5F; }
        .text-primary { color: #FF385C; }
        .border-primary { border-color: #FF385C; }
        .bg-primary-light { background-color: #FFF5F5; }
    </style>
</head>
<body class="text-gray-800">

    <nav class="bg-white shadow-md py-4 px-6">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="flex items-center space-x-2">
                <i class="fas fa-home text-3xl text-primary"></i>
                <span class="text-2xl font-bold text-gray-900">Stay<span class="text-primary">Ease</span></span>
            </a>
            
            <div class="hidden md:flex space-x-8">
                <a href="index.php" class="font-medium hover:text-primary">Home</a>
                <a href="index.php?search=1" class="font-medium hover:text-primary">All Rentals</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'hote'): ?>
                        <a href="dashboard/dashboardHote.php" class="font-medium hover:text-primary">Host Dashboard</a>
                    <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="dashboard/dashboardAdmin.php" class="font-medium hover:text-primary">Admin Dashboard</a>
                    <?php else: ?>
                        <a href="dashboard/dashboardVoyageur.php" class="font-medium hover:text-primary">My Dashboard</a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="#" class="font-medium hover:text-primary">Help</a>
            </div>
            
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="hidden md:flex items-center space-x-4">
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">
                            <a class="cursor-pointer" href="dashboard/edit_profile.php"><i class="fas fa-user mr-1"></i></a> 
                            <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </span>
                        <a href="auth/logout.php" class="px-4 py-2 bg-red-600 text-white font-medium rounded-full hover:bg-red-400 transition">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="auth/login.php" class="px-4 py-2 bg-red-600 text-white font-medium rounded-full hover:bg-red-400 transition">Login</a>
                        <a href="auth/signup.php" class="px-4 py-2 bg-red-600 text-white font-medium rounded-full hover:bg-red-400 transition">Sign up</a>
                    </div>
                <?php endif; ?>
                <button id="mobileMenuBtn" class="md:hidden text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <div id="mobileMenu" class="md:hidden hidden mt-4 pb-4">
            <div class="flex flex-col space-y-4">
                <a href="index.php" class="font-medium hover:text-primary">Home</a>
                <a href="index.php?search=1" class="font-medium hover:text-primary">All Rentals</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'hote'): ?>
                        <a href="dashboard/dashboardHote.php" class="font-medium hover:text-primary">Host Dashboard</a>
                    <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="dashboard/dashboardAdmin.php" class="font-medium hover:text-primary">Admin Dashboard</a>
                    <?php else: ?>
                        <a href="dashboard/dashboardVoyageur.php" class="font-medium hover:text-primary">My Dashboard</a>
                    <?php endif; ?>
                    <div class="pt-4 border-t">
                        <a href="auth/logout.php" class="block text-center px-4 py-2 bg-red-600 text-white font-medium rounded-full hover:bg-red-400 transition">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="pt-4 border-t">
                        <a href="auth/login.php" class="block text-center px-4 py-2 bg-red-600 text-white font-medium rounded-full hover:bg-red-400 transition">Login</a>
                        <a href="auth/signup.php" class="block text-center mt-2 px-4 py-2 bg-red-600 text-white font-medium rounded-full hover:bg-red-400 transition">Sign up</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <section class="hero-bg bg-cover bg-center text-white py-24 px-6">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Find your perfect getaway</h1>
            <p class="text-xl mb-10 max-w-2xl mx-auto">Book unique homes, apartments, and experiences for your next vacation.</p>
            
            <div class="bg-white rounded-2xl shadow-2xl p-4 md:p-6 max-w-4xl mx-auto text-black">
                <form method="GET" action="index.php" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="hidden" name="search" value="1">
                    <div class="md:col-span-1">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Location</label>
                        <input type="text" name="city" placeholder="Where are you going?" 
                               value="<?= isset($_GET['city']) ? htmlspecialchars($_GET['city']) : '' ?>" 
                               class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Check-in</label>
                        <input type="date" name="start_date" 
                               value="<?= isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '' ?>" 
                               class="w-full p-3 text-gray-400 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Check-out</label>
                        <input type="date" name="end_date" 
                               value="<?= isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '' ?>" 
                               class="w-full p-3 border text-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Guests</label>
                        <div class="flex items-center border rounded-lg p-3">
                            <i class="fas fa-user text-gray-500 mr-2"></i>
                            <select name="guests" class="w-full focus:outline-none text-gray-400">
                                <option value="">Any</option>
                                <option value="1" <?= (isset($_GET['guests']) && $_GET['guests'] == 1) ? 'selected' : '' ?>>1 guest</option>
                                <option value="2" <?= (isset($_GET['guests']) && $_GET['guests'] == 2) ? 'selected' : '' ?>>2 guests</option>
                                <option value="3" <?= (isset($_GET['guests']) && $_GET['guests'] == 3) ? 'selected' : '' ?>>3 guests</option>
                                <option value="4" <?= (isset($_GET['guests']) && $_GET['guests'] == 4) ? 'selected' : '' ?>>4+ guests</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="mt-6 w-full md:w-auto px-8 py-4 bg-red-600 text-white font-bold rounded-xl hover:bg-red-400 transition flex items-center justify-center mx-auto">
                        <i class="fas fa-search mr-2"></i> Search Rentals
                    </button>
                </form>
                
                <?php if (isset($_GET['search'])): ?>
                <div class="mt-6 flex flex-wrap gap-2 justify-center">
                    <?php if (!empty($_GET['city'])): ?>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">City: <?= htmlspecialchars($_GET['city']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($_GET['start_date'])): ?>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">From: <?= htmlspecialchars($_GET['start_date']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($_GET['end_date'])): ?>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">To: <?= htmlspecialchars($_GET['end_date']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($_GET['guests'])): ?>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Guests: <?= htmlspecialchars($_GET['guests']) ?></span>
                    <?php endif; ?>
                    <a href="index.php" class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm hover:bg-red-200">
                        <i class="fas fa-times mr-1"></i> Clear filters
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="py-16 px-6">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-3xl font-bold">
                    <?php if (isset($_GET['search'])): ?>
                        Search Results (<?= count($searchResults) ?> found)
                    <?php else: ?>
                        Featured Rentals
                    <?php endif; ?>
                </h2>
                <?php if (!isset($_GET['search'])): ?>
                    <a href="index.php?search=1" class="text-primary font-medium hover:underline">View all <i class="fas fa-arrow-right ml-1"></i></a>
                <?php endif; ?>
            </div>
            
            <?php if (empty($searchResults)): ?>
                <div class="text-center py-12">
                    <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No rentals found</h3>
                    <p class="text-gray-600">Try adjusting your search criteria or browse all available rentals.</p>
                    <a href="index.php" class="inline-block mt-4 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-400">
                        Browse All Rentals
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($searchResults as $logement): 
                        $isFavorite = isset($_SESSION['user_id']) ? $favorites->isFavorite($_SESSION['user_id'], $logement['logement_id']) : false;
                    ?>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="relative">
                            <a href="logement_detail.php?id=<?= $logement['logement_id'] ?>">
                                <img src="<?= htmlspecialchars($logement['imageUrl']) ?>" alt="<?= htmlspecialchars($logement['title']) ?>" class="w-full h-48 object-cover">
                            </a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <form method="POST" action="favorites.php" class="absolute top-4 right-4">
                                <input type="hidden" name="logement_id" value="<?= $logement['logement_id'] ?>">
                                <input type="hidden" name="action" value="<?= $isFavorite ? 'remove' : 'add' ?>">
                                <button type="submit" class="text-2xl <?= $isFavorite ? 'text-primary' : 'text-gray-300 hover:text-primary' ?>">
                                    <i class="<?= $isFavorite ? 'fas' : 'far' ?> fa-heart"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            <span class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-medium shadow">
                                $<?= number_format($logement['price'], 2) ?>/night
                            </span>
                        </div>
                        <div class="p-5">
                            <a href="logement_detail.php?id=<?= $logement['logement_id'] ?>" class="block">
                                <h3 class="font-bold text-lg mb-1 hover:text-primary transition"><?= htmlspecialchars($logement['title']) ?></h3>
                            </a>
                            <p class="text-gray-600 mb-3 text-sm">
                                <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                <?= htmlspecialchars($logement['location']) ?>
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <?php if ($logement['average_rating'] > 0): ?>
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span class="font-medium"><?= number_format($logement['average_rating'], 1) ?></span>
                                    <?php else: ?>
                                        <span class="text-gray-500 text-sm">No reviews yet</span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-sm bg-gray-100 px-2 py-1 rounded">
                                    <i class="fas fa-user-friends mr-1"></i> <?= $logement['capacity'] ?> guests
                                </span>
                            </div>
                            <div class="mt-4 pt-4 border-t">
                                <p class="text-gray-500 text-sm">Hosted by <?= htmlspecialchars($logement['first_name'] . ' ' . $logement['last_name']) ?></p>
                                <a href="logement_detail.php?id=<?= $logement['logement_id'] ?>" class="block mt-3 w-full text-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-400 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (isset($_GET['search']) && isset($totalPages) && $totalPages > 1): ?>
                <div class="mt-12 flex justify-center">
                    <nav class="flex items-center space-x-2">
                        <?php if ($page > 1): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="px-4 py-2 border rounded hover:bg-gray-100">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="px-4 py-2 bg-primary text-white rounded"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="px-4 py-2 border rounded hover:bg-gray-100"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="px-4 py-2 border rounded hover:bg-gray-100">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="py-16 px-6 bg-primary-light">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose StayEase?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-primary-light text-primary rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Secure Booking</h3>
                    <p class="text-gray-600">Your reservations are protected with our secure payment system and cancellation policies.</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-primary-light text-primary rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Verified Properties</h3>
                    <p class="text-gray-600">Every listing is carefully reviewed to ensure quality and accuracy for our guests.</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-primary-light text-primary rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">24/7 Support</h3>
                    <p class="text-gray-600">Our customer service team is available around the clock to assist with any issues.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-12 px-6">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <a href="index.php" class="flex items-center space-x-2 mb-6">
                        <i class="fas fa-home text-3xl text-primary"></i>
                        <span class="text-2xl font-bold">Stay<span class="text-primary">Ease</span></span>
                    </a>
                    <p class="text-gray-400">Your trusted platform for unique vacation rentals around the world.</p>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-6">Explore</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="index.php?search=1" class="hover:text-white transition">Popular Destinations</a></li>
                        <li><a href="#" class="hover:text-white transition">Luxury Rentals</a></li>
                        <li><a href="#" class="hover:text-white transition">Budget Stays</a></li>
                        <li><a href="#" class="hover:text-white transition">Experiences</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-6">Hosting</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="auth/signup.php?role=hote" class="hover:text-white transition">Become a Host</a></li>
                        <li><a href="#" class="hover:text-white transition">Host Resources</a></li>
                        <li><a href="#" class="hover:text-white transition">Safety Standards</a></li>
                        <li><a href="#" class="hover:text-white transition">Host Protection</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-6">Contact</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-primary"></i>
                            <span>support@stayease.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-primary"></i>
                            <span>+1 (555) 123-4567</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-primary"></i>
                            <span>123 Rental Street, San Francisco, CA</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-10 pt-8 text-center text-gray-400">
                <p>&copy; 2026 StayEase. All rights reserved. | <a href="#" class="hover:text-white">Privacy Policy</a> | <a href="#" class="hover:text-white">Terms of Service</a></p>
                <div class="flex justify-center space-x-6 mt-6 text-2xl">
                    <a href="#" class="hover:text-primary"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-primary"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-primary"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-primary"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });

        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="start_date"]').min = today;
        document.querySelector('input[name="end_date"]').min = today;

        document.querySelector('input[name="start_date"]').addEventListener('change', function() {
            document.querySelector('input[name="end_date"]').min = this.value;
        });
    </script>
</body>
</html>