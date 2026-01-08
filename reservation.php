<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

require_once __DIR__ . '/classes/Reservation.php';
require_once __DIR__ . '/classes/Logement.php';

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['user_role'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel_reservation'])) {
        $reservationId = (int)$_POST['reservation_id'];
        $reason = $_POST['cancel_reason'] ?? '';
        
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT * FROM reservation WHERE reservation_id = :reservation_id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':reservation_id' => $reservationId]);
            $reservation = $stmt->fetch();
            
            if ($reservation) {
                if ($userRole === 'admin' || $reservation['voyageur_id'] == $userId) {
                    $cancelStmt = $db->prepare("UPDATE reservation SET status = 'cancelled', cancel_reason = :reason, cancel_user_id = :user_id WHERE reservation_id = :reservation_id");
                    $cancelStmt->execute([':reason' => $reason,':user_id' => $userId,':reservation_id' => $reservationId]);
                    
                    $message = "Reservation cancelled successfully.";
                } else {
                    $message = "You don't have permission to cancel this reservation.";
                }
            }
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
    
    if (isset($_POST['confirm_reservation'])) {
        $reservationId = (int)$_POST['reservation_id'];
        
        try {
            $db = Database::getInstance()->getConnection();
            
            $sql = "SELECT r.*, l.hote_id 
                    FROM reservation r 
                    JOIN logement l ON r.logement_id = l.logement_id 
                    WHERE r.reservation_id = :reservation_id";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':reservation_id' => $reservationId]);
            $reservation = $stmt->fetch();
            
            if ($reservation && ($userRole === 'admin' || $reservation['hote_id'] == $userId)) {
                $updateStmt = $db->prepare("UPDATE reservation SET status = 'confirmed' WHERE reservation_id = :reservation_id");
                $updateStmt->execute([':reservation_id' => $reservationId]);
                $message = "Reservation confirmed successfully.";
            } else {
                $message = "You don't have permission to confirm this reservation.";
            }
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

$reservations = [];
try {
    $db = Database::getInstance()->getConnection();
    
    if ($userRole === 'voyageur') {
        $sql = "SELECT r.*, l.title, l.imageUrl, l.price, u.first_name as hote_first_name, u.last_name as hote_last_name 
                FROM reservation r 
                JOIN logement l ON r.logement_id = l.logement_id 
                JOIN users u ON l.hote_id = u.user_id 
                WHERE r.voyageur_id = :user_id 
                ORDER BY r.created_at DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $reservations = $stmt->fetchAll();
    } 
    elseif ($userRole === 'hote') {
        $sql = "SELECT r.*, l.title, l.imageUrl, l.price, u.first_name as voyageur_first_name, u.last_name as voyageur_last_name 
                FROM reservation r 
                JOIN logement l ON r.logement_id = l.logement_id 
                JOIN users u ON r.voyageur_id = u.user_id 
                WHERE l.hote_id = :user_id 
                ORDER BY r.created_at DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $reservations = $stmt->fetchAll();
    }
    elseif ($userRole === 'admin') {
        $sql = "SELECT r.*, l.title, l.imageUrl, l.price, 
                       u1.first_name as voyageur_first_name, u1.last_name as voyageur_last_name,
                       u2.first_name as hote_first_name, u2.last_name as hote_last_name 
                FROM reservation r 
                JOIN logement l ON r.logement_id = l.logement_id 
                JOIN users u1 ON r.voyageur_id = u1.user_id 
                JOIN users u2 ON l.hote_id = u2.user_id 
                ORDER BY r.created_at DESC";
        
        $stmt = $db->query($sql);
        $reservations = $stmt->fetchAll();
    }
} catch (Exception $e) {
    $message = "Error loading reservations: " . $e->getMessage();
}
?>