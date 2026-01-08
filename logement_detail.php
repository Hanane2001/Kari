<?php
session_start();
require_once __DIR__ . '/classes/Logement.php';
require_once __DIR__ . '/classes/Reservation.php';
require_once __DIR__ . '/classes/Review.php';
require_once __DIR__ . '/classes/Favorites.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$logementId = (int)$_GET['id'];
$logement = Logement::getById($logementId);

if (!$logement) {
    header("Location: index.php");
    exit();
}

$reviews = Review::getByLogement($logementId);
$averageRating = $logement['average_rating'];

$isFavorite = false;
if (isset($_SESSION['user_id'])) {
    $favorites = new Favorites();
    $isFavorite = $favorites->isFavorite($_SESSION['user_id'], $logementId);
}

$bookingError = '';
$bookingSuccess = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_now'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: auth/login.php?redirect=logement_detail.php?id=" . $logementId);
        exit();
    }

    if ($_SESSION['user_role'] === 'hote' || $_SESSION['user_role'] === 'admin') {
        $bookingError = "Hosts and admins cannot book properties.";
    } else {
        try {
            $startDate = new DateTime($_POST['start_date']);
            $endDate = new DateTime($_POST['end_date']);
            $guests = (int)$_POST['guests'];
            
            if ($guests > $logement['capacity']) {
                $bookingError = "Number of guests exceeds property capacity ({$logement['capacity']}).";
            } else {
                $nights = $startDate->diff($endDate)->days;
                $totalPrice = $nights * $logement['price'];

                $reservation = new Reservation($logementId, $_SESSION['user_id'], $startDate, $endDate, $guests, $logement['price']);
                
                if ($reservation->save()) {
                    $bookingSuccess = "Reservation successful! Total: $" . number_format($totalPrice, 2);
                } else {
                    $bookingError = "Failed to create reservation. Please try again.";
                }
            }
        } catch (Exception $e) {
            $bookingError = $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_action'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: auth/login.php?redirect=logement_detail.php?id=" . $logementId);
        exit();
    }
    
    $favorites = new Favorites();
    if ($_POST['favorite_action'] === 'add') {
        if ($favorites->addFavorite($_SESSION['user_id'], $logementId)) {
            $isFavorite = true;
        }
    } else {
        if ($favorites->removeFavorite($_SESSION['user_id'], $logementId)) {
            $isFavorite = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($logement['title']) ?> - StayEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-md py-4 px-6">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="flex items-center space-x-2">
                <i class="fas fa-home text-2xl text-red-500"></i>
                <span class="text-xl font-bold">Stay<span class="text-red-500">Ease</span></span>
            </a>
            <div class="flex items-center space-x-4">
                <a href="index.php" class="px-4 py-2 text-gray-700 bg-red-600 rounded text-white hover:bg-red-400">Back to Rentals</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-gray-700">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <a href="auth/logout.php" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Logout</a>
                <?php else: ?>
                    <a href="auth/login.php" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <?php if ($bookingError): ?>
            <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded">
                <?= htmlspecialchars($bookingError) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($bookingSuccess): ?>
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded">
                <?= htmlspecialchars($bookingSuccess) ?>
                <a href="dashboard/dashboardVoyageur.php" class="ml-4 text-green-800 font-medium hover:underline">View my reservations</a>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="relative h-96">
                <img src="<?= htmlspecialchars($logement['imageUrl']) ?>" alt="<?= htmlspecialchars($logement['title']) ?>" class="w-full h-full object-cover">
                <div class="absolute top-4 right-4">
                    <form method="POST">
                        <input type="hidden" name="favorite_action" value="<?= $isFavorite ? 'remove' : 'add' ?>">
                        <button type="submit" class="text-3xl <?= $isFavorite ? 'text-red-500' : 'text-white' ?> hover:text-red-400">
                            <i class="<?= $isFavorite ? 'fas' : 'far' ?> fa-heart"></i>
                        </button>
                    </form>
                </div>
                <div class="absolute bottom-4 left-4 bg-white px-4 py-2 rounded-lg shadow">
                    <span class="text-2xl font-bold text-red-600">$<?= number_format($logement['price'], 2) ?></span>
                    <span class="text-gray-600"> / night</span>
                </div>
            </div>

            <div class="p-8">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
                    <div class="lg:w-2/3">
                        <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($logement['title']) ?></h1>
                        
                        <div class="flex items-center space-x-6 mb-6">
                            <div class="flex items-center">
                                <?php if ($averageRating > 0): ?>
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="font-medium"><?= number_format($averageRating, 1) ?></span>
                                    <span class="text-gray-500 ml-1">(<?// count($reviews) ?> reviews)</span>
                                <?php else: ?>
                                    <span class="text-gray-500">No reviews yet</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                <span><?= htmlspecialchars($logement['location']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user-friends text-gray-400 mr-2"></i>
                                <span>Up to <?= $logement['capacity'] ?> guests</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-home text-gray-400 mr-2"></i>
                                <span><?= ucfirst($logement['type']) ?></span>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-xl font-bold mb-3">About this place</h2>
                            <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($logement['description'])) ?></p>
                        </div>

                        <div class="border-t pt-6">
                            <h2 class="text-xl font-bold mb-4">Meet your host</h2>
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xl mr-4">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold"><?= htmlspecialchars($logement['first_name'] . ' ' . $logement['last_name']) ?></h3>
                                    <p class="text-gray-600">Host since <?= date('Y', strtotime($logement['created_at'])) ?></p>
                                    <?php if (!empty($logement['phone'])): ?>
                                        <p class="text-gray-600 mt-1">
                                            <i class="fas fa-phone mr-2"></i><?= htmlspecialchars($logement['phone']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($reviews)): ?>
                        <div class="border-t pt-6 mt-6">
                            <h2 class="text-xl font-bold mb-4">Reviews</h2>
                            <div class="space-y-6">
                                <?php foreach ($reviews as $review): ?>
                                <div class="border-b pb-6">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold"><?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?></h4>
                                            <div class="flex items-center">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' ?>"></i>
                                                <?php endfor; ?>
                                                <span class="text-gray-500 text-sm ml-2">
                                                    <?= date('M Y', strtotime($review['created_at'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!empty($review['comment'])): ?>
                                        <p class="text-gray-700"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="lg:w-1/3">
                        <div class="border rounded-2xl p-6 shadow-md sticky top-4">
                            <h2 class="text-xl font-bold mb-4">Book this property</h2>
                            <form method="POST" action="">
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Check-in</label>
                                            <input type="date" name="start_date" required 
                                                   min="<?= date('Y-m-d') ?>" 
                                                   class="w-full p-3 border rounded-lg">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Check-out</label>
                                            <input type="date" name="end_date" required 
                                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                                                   class="w-full p-3 border rounded-lg">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Guests</label>
                                        <select name="guests" required class="w-full p-3 border rounded-lg">
                                            <?php for ($i = 1; $i <= $logement['capacity']; $i++): ?>
                                                <option value="<?= $i ?>"><?= $i ?> guest<?= $i > 1 ? 's' : '' ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="pt-4 border-t">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-gray-600">$<?= number_format($logement['price'], 2) ?> x <span id="nights">0</span> nights</span>
                                            <span id="subtotal">$0.00</span>
                                        </div>
                                        <div class="flex justify-between mb-2">
                                            <span class="text-gray-600">Service fee</span>
                                            <span id="service_fee">$0.00</span>
                                        </div>
                                        <div class="flex justify-between font-bold text-lg pt-4 border-t">
                                            <span>Total</span>
                                            <span id="total_price">$0.00</span>
                                        </div>
                                    </div>
                                    
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <?php if ($_SESSION['user_role'] === 'voyageur'): ?>
                                            <button type="submit" name="book_now" class="w-full mt-4 py-4 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600">
                                                Book Now
                                            </button>
                                        <?php else: ?>
                                            <button type="button" disabled class="w-full mt-4 py-4 bg-gray-400 text-white font-bold rounded-xl cursor-not-allowed">
                                                Only travelers can book
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="auth/login.php?redirect=logement_detail.php?id=<?= $logementId ?>" 
                                           class="block w-full mt-4 py-4 bg-red-500 text-white font-bold text-center rounded-xl hover:bg-red-600">
                                            Login to Book
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                            
                            <div class="mt-6 text-center text-gray-500 text-sm">
                                <p>You won't be charged yet</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $similarLogements = Logement::getAllAvailable($logement['location'], null, $logement['price'] * 1.5, null, null, null, 4, 0);
        if (!empty($similarLogements)):
        ?>
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Similar properties in <?= htmlspecialchars($logement['location']) ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($similarLogements as $similar): 
                    if ($similar['logement_id'] == $logementId) continue;
                ?>
                <a href="logement_detail.php?id=<?= $similar['logement_id'] ?>" class="block">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                        <img src="<?= htmlspecialchars($similar['imageUrl']) ?>" alt="<?= htmlspecialchars($similar['title']) ?>" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold mb-1"><?= htmlspecialchars($similar['title']) ?></h3>
                            <p class="text-gray-600 text-sm mb-2"><?= htmlspecialchars($similar['location']) ?></p>
                            <div class="flex justify-between items-center">
                                <span class="text-red-600 font-bold">$<?= number_format($similar['price'], 2) ?>/night</span>
                                <?php if ($similar['average_rating'] > 0): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span class="text-sm"><?= number_format($similar['average_rating'], 1) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        const pricePerNight = <?= $logement['price'] ?>;
        
        function calculatePrice() {
            const startDate = new Date(document.querySelector('input[name="start_date"]').value);
            const endDate = new Date(document.querySelector('input[name="end_date"]').value);
            
            if (startDate && endDate && startDate < endDate) {
                const nights = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                const subtotal = pricePerNight * nights;
                const serviceFee = subtotal * 0.12; // 12% service fee
                const total = subtotal + serviceFee;
                
                document.getElementById('nights').textContent = nights;
                document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
                document.getElementById('service_fee').textContent = '$' + serviceFee.toFixed(2);
                document.getElementById('total_price').textContent = '$' + total.toFixed(2);
            }
        }
 
        document.querySelectorAll('input[name="start_date"], input[name="end_date"]').forEach(input => {
            input.addEventListener('change', calculatePrice);
        });
        
        calculatePrice();

        document.querySelector('input[name="start_date"]').addEventListener('change', function() {
            const endDateInput = document.querySelector('input[name="end_date"]');
            endDateInput.min = this.value;
            
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = '';
            }
            
            calculatePrice();
        });
    </script>
</body>
</html>