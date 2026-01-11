<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../classes/Reservation.php';
require_once __DIR__ . '/../classes/Favorites.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Review.php';

$userId = $_SESSION['user_id'];
$user = User::getById($userId);
$reservations = Reservation::getByVoyageur($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['reason_cancel'])) {
    $reservationId = (int) $_POST['reservation_id'];
    $reasonCancel  = trim($_POST['reason_cancel']);
    foreach ($reservations as $reservation) {
        if ($reservation['reservation_id'] == $reservationId) {
            $res = new Reservation((int)$reservation['logement_id'], (int)$reservation['voyageur_id'], new DateTime($reservation['start_date']), new DateTime($reservation['end_date']), (int)$reservation['nbr_guests'], (float)($reservation['total_price'] / (date_diff(new DateTime($reservation['start_date']), new DateTime($reservation['end_date']))->days)));
            $res->setId($reservationId);
            if ($res->cancel($userId, $reasonCancel)) {
                $_SESSION['success'] = "Reservation cancelled successfully.";
                header("Location: dashboardVoyageur.php");
                exit();
            } else {
                $_SESSION['error'] = "Failed to cancel reservation.";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    $reservationId = (int) $_POST['reservation_id'];
    $rating = (int) $_POST['rating'];
    $comment = trim($_POST['comment']);
    
    try {
        $reservation = null;
        foreach ($reservations as $res) {
            if ($res['reservation_id'] == $reservationId) {
                $reservation = $res;
                break;
            }
        }
        
        if ($reservation && $reservation['status'] === 'completed') {
            $review = new Review( (int)$reservation['logement_id'], $userId, $reservationId, $rating, $comment);
            
            if ($review->save()) {
                $_SESSION['success'] = "Review submitted successfully!";
                header("Location: dashboardVoyageur.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "You can only review completed reservations.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_favorite'])) {
    $logementId = (int) $_POST['logement_id'];
    $favorites = new Favorites();
    
    if ($favorites->removeFavorite($userId, $logementId)) {
        $_SESSION['success'] = "Removed from favorites successfully!";
        header("Location: dashboardVoyageur.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to remove from favorites.";
    }
}

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
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

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
                                    <p class="text-gray-600 text-sm">Total: $<?= number_format($reservation['total_price'], 2) ?></p>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 text-right">
                                <span class="px-3 py-1 rounded-full text-sm <?= $statusColor ?>">
                                    <?= ucfirst($reservation['status']) ?>
                                </span>
                                <div class="mt-2 space-x-2">
                                    <?php if ($reservation['status'] === 'pending' || $reservation['status'] === 'confirmed'): ?>
                                        <form method="POST" class="inline-block">
                                            <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id'] ?>">
                                            <input type="text" name="reason_cancel" class="hidden border rounded px-2 py-1 mt-2 w-full" id="reason_cancel_<?= $reservation['reservation_id'] ?>" placeholder="Reason for cancellation" required>
                                            <button type="button" class="cancel-btn px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600 mt-2" data-id="<?= $reservation['reservation_id'] ?>"><i class="fas fa-times mr-1"></i>Cancel</button>
                                            <button type="submit" class="hidden submit-btn px-3 py-1 bg-gray-700 text-white rounded text-sm mt-2" id="submit_<?= $reservation['reservation_id'] ?>">Confirm Cancel</button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($reservation['status'] === 'completed'): ?>
                                        <button type="button" class="px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600" onclick="openReviewModal(<?= $reservation['reservation_id'] ?>, <?= $reservation['logement_id'] ?>)">
                                            <i class="fas fa-star mr-1"></i>Leave Review
                                        </button>
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
                            <h3 class="font-bold mb-2 truncate"><?= htmlspecialchars($favorite['title']) ?></h3>
                            <p class="text-gray-600 text-sm mb-3 truncate">
                                Host: <?= htmlspecialchars($favorite['hote_first_name'] . ' ' . $favorite['hote_last_name']) ?>
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-bold">$<?= number_format($favorite['price'], 2) ?>/night</span>
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span><?= number_format($favorite['average_rating'], 1) ?></span>
                                </div>
                            </div>
                            <div class="mt-4 flex space-x-2">
                                <a href="../logement_detail.php?id=<?= $favorite['logement_id'] ?>" 
                                   class="flex-1 text-center py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <form method="POST" class="flex-1">
                                    <input type="hidden" name="logement_id" value="<?= $favorite['logement_id'] ?>">
                                    <input type="hidden" name="remove_favorite" value="1">
                                    <button type="submit" 
                                            class="w-full py-2 bg-red-500 text-white rounded hover:bg-red-600"
                                            onclick="return confirm('Are you sure you want to remove this from favorites?')">
                                        <i class="fas fa-trash mr-1"></i>Remove
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

    <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold mb-4">Leave a Review</h3>
            <form id="reviewForm" method="POST">
                <input type="hidden" name="reservation_id" id="modal_reservation_id">
                <input type="hidden" name="logement_id" id="modal_logement_id">
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Rating</label>
                    <div class="flex space-x-1" id="ratingStars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star text-2xl text-gray-300 cursor-pointer hover:text-yellow-400 rating-star" 
                               data-rating="<?= $i ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="selected_rating" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Comment (optional)</label>
                    <textarea name="comment" rows="4" 
                              class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Share your experience..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" 
                            onclick="closeReviewModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            name="add_review" 
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.cancel-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const reasonInput = document.getElementById('reason_cancel_' + id);
                const submitBtn = document.getElementById('submit_' + id);

                reasonInput.classList.remove('hidden');
                submitBtn.classList.remove('hidden');
                this.classList.add('hidden');

                reasonInput.focus();
            });
        });

        let currentReservationId = null;
        let currentLogementId = null;

        function openReviewModal(reservationId, logementId) {
            currentReservationId = reservationId;
            currentLogementId = logementId;
            
            document.getElementById('modal_reservation_id').value = reservationId;
            document.getElementById('modal_logement_id').value = logementId;
            document.getElementById('reviewModal').classList.remove('hidden');
            
            document.querySelectorAll('.rating-star').forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });
            document.getElementById('selected_rating').value = '';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
        }

        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                document.getElementById('selected_rating').value = rating;
                
                document.querySelectorAll('.rating-star').forEach(s => {
                    const starRating = parseInt(s.dataset.rating);
                    if (starRating <= rating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });
        
        document.getElementById('reviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReviewModal();
            }
        });
    </script>
</body>
</html>