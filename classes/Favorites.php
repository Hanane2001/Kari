<?php
require_once __DIR__ . '/../config/Database.php';

class Favorites {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addFavorite(int $userId, int $logementId): bool {
        try {
            $sql = "INSERT INTO favorites (user_id, logement_id) VALUES (:user_id, :logement_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':user_id' => $userId,':logement_id' => $logementId]);
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return true;
            }
            error_log("Favorites addFavorite error: " . $e->getMessage());
            return false;
        }
    }

    public function removeFavorite(int $userId, int $logementId): bool {
        try {
            $sql = "DELETE FROM favorites WHERE user_id = :user_id AND logement_id = :logement_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':user_id' => $userId,':logement_id' => $logementId]);
        } catch (PDOException $e) {
            error_log("Favorites removeFavorite error: " . $e->getMessage());
            return false;
        }
    }

    public function getFavorites(int $userId): array {
        try {
            $sql = "SELECT f.*, l.title, l.imageUrl, l.price, l.average_rating, u.first_name as hote_first_name, u.last_name as hote_last_name 
                    FROM favorites f 
                    JOIN logement l ON f.logement_id = l.logement_id 
                    JOIN users u ON l.hote_id = u.user_id 
                    WHERE f.user_id = :user_id AND l.is_active = TRUE 
                    ORDER BY f.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Favorites getFavorites error: " . $e->getMessage());
            return [];
        }
    }

    public function isFavorite(int $userId, int $logementId): bool {
        try {
            $sql = "SELECT COUNT(*) as count FROM favorites 
                    WHERE user_id = :user_id AND logement_id = :logement_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId,':logement_id' => $logementId]);
            
            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Favorites isFavorite error: " . $e->getMessage());
            return false;
        }
    }
}
?>

