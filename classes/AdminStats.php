<?php
require_once __DIR__ . '/../config/Database.php';

class AdminStats {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getDashboardStats(): array {
        try {
            $stats = [];
            
            // Total utilisateurs
            $sql = "SELECT COUNT(*) as total FROM users";
            $stmt = $this->db->query($sql);
            $stats['total_users'] = $stmt->fetch()['total'];
            
            // Total logements
            $sql = "SELECT COUNT(*) as total FROM logement WHERE is_active = TRUE";
            $stmt = $this->db->query($sql);
            $stats['total_logements'] = $stmt->fetch()['total'];
            
            // Total réservations
            $sql = "SELECT COUNT(*) as total FROM reservation";
            $stmt = $this->db->query($sql);
            $stats['total_reservations'] = $stmt->fetch()['total'];
            
            // Revenus totaux
            $sql = "SELECT COALESCE(SUM(total_price), 0) as total FROM reservation WHERE status IN ('confirmed', 'completed')";
            $stmt = $this->db->query($sql);
            $stats['total_revenue'] = $stmt->fetch()['total'];
            
            // 10 logements les plus rentables
            $sql = "SELECT l.logement_id, l.title, l.price, 
                           COUNT(r.reservation_id) as reservation_count,
                           COALESCE(SUM(r.total_price), 0) as total_income
                    FROM logement l 
                    LEFT JOIN reservation r ON l.logement_id = r.logement_id 
                    WHERE r.status IN ('confirmed', 'completed')
                    GROUP BY l.logement_id 
                    ORDER BY total_income DESC 
                    LIMIT 10";
            
            $stmt = $this->db->query($sql);
            $stats['top_logements'] = $stmt->fetchAll();
            
            // Derniers utilisateurs inscrits
            $sql = "SELECT user_id, first_name, last_name, email, role, created_at 
                    FROM users ORDER BY created_at DESC LIMIT 10";
            $stmt = $this->db->query($sql);
            $stats['recent_users'] = $stmt->fetchAll();
            
            return $stats;
        } catch (PDOException $e) {
            error_log("AdminStats getDashboardStats error: " . $e->getMessage());
            return [];
        }
    }

    public function toggleUserStatus(int $userId): bool {
        try {
            $sql = "UPDATE users SET is_active = NOT is_active WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':user_id' => $userId]);
        } catch (PDOException $e) {
            error_log("AdminStats toggleUserStatus error: " . $e->getMessage());
            return false;
        }
    }

    public function toggleLogementStatus(int $logementId): bool {
        try {
            $sql = "UPDATE logement SET is_active = NOT is_active WHERE logement_id = :logement_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':logement_id' => $logementId]);
        } catch (PDOException $e) {
            error_log("AdminStats toggleLogementStatus error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsers(): array {
        try {
            $sql = "SELECT * FROM users ORDER BY created_at DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("AdminStats getAllUsers error: " . $e->getMessage());
            return [];
        }
    }

    public function getAllLogements(): array {
        try {
            $sql = "SELECT l.*, u.first_name, u.last_name 
                    FROM logement l 
                    JOIN users u ON l.hote_id = u.user_id 
                    ORDER BY l.created_at DESC";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("AdminStats getAllLogements error: " . $e->getMessage());
            return [];
        }
    }
}
?>
