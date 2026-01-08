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
                throw new Exception("You have already left a review for this logement.");
            }

            $sql = "INSERT INTO review (logement_id, voyageur_id, reservation_id, rating, comment, is_visible) VALUES (:logement_id, :voyageur_id, :reservation_id, :rating, :comment, :is_visible)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([':logement_id' => $this->logementId,':voyageur_id' => $this->voyageurId,':reservation_id' => $this->reservationId,':rating' => $this->rating,':comment' => $this->comment,':is_visible' => $this->isVisible]);

            if ($result) {
                $this->id = (int) $this->db->lastInsertId();
                $this->updateLogementRating();
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Review save error: " . $e->getMessage());
            throw new Exception("Error recording the notice.");
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
            $sql = "SELECT r.*, u.first_name, u.last_name 
                    FROM review r 
                    JOIN users u ON r.voyageur_id = u.user_id 
                    WHERE r.logement_id = :logement_id AND r.is_visible = TRUE 
                    ORDER BY r.created_at DESC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':logement_id' => $logementId]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Review getByLogement error: " . $e->getMessage());
            return [];
        }
    }

    public function getId(): int { return $this->id; }
    public function getRating(): int { return $this->rating; }
    public function getComment(): string { return $this->comment; }
}
?>