<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'hote') {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../classes/Logement.php';
require_once __DIR__ . '/../classes/Reservation.php';

$userId = $_SESSION['user_id'];
$logements = Logement::getByHote($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_logement'])) {
    try {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $imageUrl = $_POST['image_url'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
        $type = LogementType::from($_POST['type']);
        $price = (float)$_POST['price'];
        $capacity = (int)$_POST['capacity'];
        $availableFrom = new DateTime($_POST['available_from']);
        $availableTo = new DateTime($_POST['available_to']);
        
        $logement = new Logement($userId, $title, $description, $imageUrl, $type, $price, $capacity, $availableFrom, $availableTo);
        
        if ($logement->save()) {
            $_SESSION['success'] = "Logement ajouté avec succès!";
            header("Location: dashboardHote.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEase - Host Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md py-4 px-6">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="flex items-center space-x-2">
                <i class="fas fa-home text-2xl text-green-600"></i>
                <span class="text-xl font-bold">Host<span class="text-green-600">Dashboard</span></span>
            </a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="../index.php" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Home</a>
                <a href="../auth/logout.php" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Add New Rental</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" required class="w-full p-3 border rounded">
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Type *</label>
                    <select name="type" required class="w-full p-3 border rounded">
                        <option value="apartment">Apartment</option>
                        <option value="house">House</option>
                        <option value="villa">Villa</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Description *</label>
                    <textarea name="description" required rows="3" class="w-full p-3 border rounded"></textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Price per night ($) *</label>
                    <input type="number" name="price" min="1" step="0.01" required class="w-full p-3 border rounded">
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Capacity (guests) *</label>
                    <input type="number" name="capacity" min="1" required class="w-full p-3 border rounded">
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Available From *</label>
                    <input type="date" name="available_from" required class="w-full p-3 border rounded">
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Available To *</label>
                    <input type="date" name="available_to" required class="w-full p-3 border rounded">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Image URL</label>
                    <input type="url" name="image_url" class="w-full p-3 border rounded" placeholder="https://example.com/image.jpg">
                </div>
                
                <div class="md:col-span-2">
                    <button type="submit" name="add_logement" class="w-full bg-green-600 text-white p-3 rounded hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i> Add Rental
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">My Rentals (<?= count($logements) ?>)</h2>
            
            <?php if (empty($logements)): ?>
                <p class="text-gray-500 text-center py-8">You haven't added any rentals yet.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($logements as $logement): ?>
                    <div class="border rounded-xl overflow-hidden hover:shadow-lg transition">
                        <img src="<?= htmlspecialchars($logement['imageUrl']) ?>" alt="<?= htmlspecialchars($logement['title']) ?>" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2"><?= htmlspecialchars($logement['title']) ?></h3>
                            <p class="text-gray-600 mb-3"><?= substr($logement['description'], 0, 100) ?>...</p>
                            <div class="flex justify-between items-center">
                                <span class="text-green-600 font-bold">$<?= number_format($logement['price'], 2) ?>/night</span>
                                <span class="px-3 py-1 rounded-full text-sm <?= $logement['is_active'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= $logement['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                            <div class="mt-4 flex space-x-2">
                                <a href="edit_logement.php?id=<?= $logement['logement_id'] ?>" class="flex-1 text-center py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                                <form method="POST" class="flex-1">
                                    <input type="hidden" name="logement_id" value="<?= $logement['logement_id'] ?>">
                                    <button type="submit" name="toggle_status" class="w-full py-2 <?= $logement['is_active'] ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' ?> text-white rounded">
                                        <?= $logement['is_active'] ? 'Deactivate' : 'Activate' ?>
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