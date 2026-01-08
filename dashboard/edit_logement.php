<?php
session_start();
require_once '../classes/Logement.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'hote') {
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$logementId = $_GET['id'] ?? null;

if (!$logementId) {
    header("Location: dashboardHote.php");
    exit();
}

$logement = Logement::getById($logementId);

if (!$logement || $logement['hote_id'] != $userId) {
    $_SESSION['error'] = "Accommodation not found or you don't have permission to modify it.";
    header("Location: dashboardHote.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_logement'])) {
        try {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $imageUrl = $_POST['image_url'] ?? 'https://images.unsplash.com/photo-1568605114967-8130f3a36994';
            $type = LogementType::from($_POST['type']);
            $price = (float)$_POST['price'];
            $capacity = (int)$_POST['capacity'];
            $availableFrom = new DateTime($_POST['available_from']);
            $availableTo = new DateTime($_POST['available_to']);

            $updatedLogement = new Logement($userId, $title, $description, $imageUrl, $type, $price, $capacity, $availableFrom, $availableTo);
            
            if ($updatedLogement->update($logementId, $userId)) {
                $_SESSION['success'] = "Accommodation updated successfully!";
                header("Location: dashboardHote.php");
                exit();
            } else {
                $_SESSION['error'] = "Failed to update accommodation.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    } 
    elseif (isset($_POST['delete_logement'])) {
        if (Logement::delete($logementId, $userId)) {
            $_SESSION['success'] = "Accommodation deleted successfully!";
            header("Location: dashboardHote.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to delete accommodation.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEase - Edit Accommodation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="form-bg flex items-center justify-center p-4">
        <div class="w-full max-w-4xl mx-auto">
            <div class="mb-8">
                <a href="dashboardHote.php" class="font-medium text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Host Dashboard
                </a>
            </div>
            
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
                    <h2 class="text-xl font-bold mb-4">Edit Accommodation</h2>
                    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Title *</label>
                            <input type="text" name="title" required class="w-full p-3 border rounded" 
                                   value="<?= htmlspecialchars($logement['title'] ?? '') ?>">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2">Type *</label>
                            <select name="type" required class="w-full p-3 border rounded">
                                <option value="apartment" <?= ($logement['type'] ?? '') == 'apartment' ? 'selected' : '' ?>>Apartment</option>
                                <option value="house" <?= ($logement['type'] ?? '') == 'house' ? 'selected' : '' ?>>House</option>
                                <option value="villa" <?= ($logement['type'] ?? '') == 'villa' ? 'selected' : '' ?>>Villa</option>
                                <option value="other" <?= ($logement['type'] ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Description *</label>
                            <textarea name="description" required rows="3" class="w-full p-3 border rounded"><?= htmlspecialchars($logement['description'] ?? '') ?></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2">Price per night ($) *</label>
                            <input type="number" name="price" min="1" step="0.01" required 
                                   class="w-full p-3 border rounded" value="<?= htmlspecialchars($logement['price'] ?? '') ?>">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2">Capacity (guests) *</label>
                            <input type="number" name="capacity" min="1" required 
                                   class="w-full p-3 border rounded" value="<?= htmlspecialchars($logement['capacity'] ?? '') ?>">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2">Available From *</label>
                            <?php 
                            $availableFrom = isset($logement['available_from']) ? new DateTime($logement['available_from']) : null;
                            ?>
                            <input type="date" name="available_from" required class="w-full p-3 border rounded" 
                                   value="<?= $availableFrom ? $availableFrom->format('Y-m-d') : '' ?>">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2">Available To *</label>
                            <?php 
                            $availableTo = isset($logement['available_to']) ? new DateTime($logement['available_to']) : null;
                            ?>
                            <input type="date" name="available_to" required class="w-full p-3 border rounded" 
                                   value="<?= $availableTo ? $availableTo->format('Y-m-d') : '' ?>">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Image URL</label>
                            <input type="url" name="image_url" class="w-full p-3 border rounded" 
                                   placeholder="https://example.com/image.jpg" 
                                   value="<?= htmlspecialchars($logement['imageUrl'] ?? '') ?>">
                        </div>
                        
                        <div class="flex gap-2 md:col-span-2">
                            <button type="submit" name="save_logement" 
                                    class="w-1/2 bg-green-600 text-white p-3 rounded hover:bg-green-700 transition">
                                <i class="fa-solid fa-floppy-disk mr-2"></i> Save Changes
                            </button>
                            <button type="submit" name="delete_logement" 
                                    class="w-1/2 bg-red-600 text-white p-3 rounded hover:bg-red-700 transition"
                                    onclick="return confirm('Are you sure you want to delete this accommodation? This action cannot be undone.')">
                                <i class="fa-solid fa-trash mr-2"></i> Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>