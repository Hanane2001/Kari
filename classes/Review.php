<?php
require_once __DIR__ . '/../config/Database.php';

class Review {
    private int $id;
    private int $logementId;
    private int $voyageurId;
    private int $reservationId;
    private int $rating;
    private string $comment;
    private bool $isVisible;
    private PDO $db;

    public function __construct(int $logementId, int $voyageurId, int $reservationId, int $rating, string $comment = "") {
        if ($rating < 1 || $rating > 5) {
            throw new Exception("The rating must be between 1 and 5.");
        }
        $this->logementId = $logementId;
        $this->voyageurId = $voyageurId;
        $this->reservationId = $reservationId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->isVisible = true;
        $this->db = Database::getInstance()->getConnection();
    }

    public function save(): bool {
        try {
            $checkSql = "SELECT COUNT(*) as count FROM review WHERE reservation_id = :reservation_id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([':reservation_id' => $this->reservationId]);
            $result = $checkStmt->fetch();

            if ($result['count'] > 0) {
                throw new Exception("You have already left a review for this reservation.");
            }

            $checkReservationSql = "SELECT status FROM reservation WHERE reservation_id = :reservation_id";
            $checkReservationStmt = $this->db->prepare($checkReservationSql);
            $checkReservationStmt->execute([':reservation_id' => $this->reservationId]);
            $reservation = $checkReservationStmt->fetch();

            if (!$reservation || $reservation['status'] !== 'completed') {
                throw new Exception("You can only review completed reservations.");
            }

            $sql = "INSERT INTO review (logement_id, voyageur_id, reservation_id, rating, comment, is_visible) VALUES (:logement_id, :voyageur_id, :reservation_id, :rating, :comment, :is_visible)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([':logement_id' => $this->logementId,':voyageur_id' => $this->voyageurId,':reservation_id' => $this->reservationId,':rating' => $this->rating,':comment' => $this->comment,':is_visible' => (int)$this->isVisible]);

            if ($result) {
                $this->id = (int) $this->db->lastInsertId();
                $this->updateLogementRating();
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Review save error: " . $e->getMessage());
            throw new Exception("Error recording the review.");
        }
    }

    private function updateLogementRating(): void {
        try {
            $sql = "UPDATE logement SET average_rating = (
                        SELECT COALESCE(AVG(rating), 0) 
                        FROM review 
                        WHERE logement_id = :logement_id AND is_visible = TRUE
                    ) WHERE logement_id = :logement_id2";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':logement_id' => $this->logementId,':logement_id2' => $this->logementId]);
        } catch (PDOException $e) {
            error_log("Review updateLogementRating error: " . $e->getMessage());
        }
    }

    public static function getByLogement(int $logementId): array {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT r.*, u.first_name, u.last_name, u.email 
                    FROM review r 
                    JOIN users u ON r.voyageur_id = u.user_id 
                    WHERE r.logement_id = :logement_id AND r.is_visible = TRUE 
                    ORDER BY r.created_at DESC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':logement_id' => $logementId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Review getByLogement error: " . $e->getMessage());
            return [];
        }
    }

    public static function canUserReview(int $userId, int $reservationId): bool {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT COUNT(*) as count FROM reservation 
                    WHERE reservation_id = :reservation_id 
                    AND voyageur_id = :user_id 
                    AND status = 'completed'";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':reservation_id' => $reservationId,':user_id' => $userId]);
            
            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Review canUserReview error: " . $e->getMessage());
            return false;
        }
    }

    public static function getByUser(int $userId): array {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT r.*, l.title, l.imageUrl 
                    FROM review r 
                    JOIN logement l ON r.logement_id = l.logement_id 
                    WHERE r.voyageur_id = :user_id 
                    ORDER BY r.created_at DESC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Review getByUser error: " . $e->getMessage());
            return [];
        }
    }

    public function getId(): int { return $this->id; }
    public function getRating(): int { return $this->rating; }
    public function getComment(): string { return $this->comment; }
}
?>