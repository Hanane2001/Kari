<?php
require_once __DIR__ . '/../config/Database.php';

enum ReservationStatus: string {
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
}

class Reservation {
    private int $id;
    private int $logementId;
    private int $voyageurId;
    private DateTime $startDate;
    private DateTime $endDate;
    private int $nbrGuests;
    private float $totalPrice;
    private ReservationStatus $status;
    private string $cancelReason;
    private int $cancelUserId;
    private PDO $db;

    public function __construct(int $logementId, int $voyageurId, DateTime $startDate, DateTime $endDate, int $nbrGuests, float $pricePerNight) {
        $this->logementId = $logementId;
        $this->voyageurId = $voyageurId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->nbrGuests = $nbrGuests;

        $interval = $startDate->diff($endDate);
        $nights = $interval->days;
        $this->totalPrice = $nights * $pricePerNight;
        
        $this->status = ReservationStatus::PENDING;
        $this->db = Database::getInstance()->getConnection();
    }

    public function save(): bool {
        if (!$this->checkAvailability()) {
            throw new Exception("Logement is not available for these dates.");
        }

        try {
            $sql = "INSERT INTO reservation (logement_id, voyageur_id, start_date, end_date, nbr_guests, total_price, status) VALUES (:logement_id, :voyageur_id, :start_date, :end_date, :nbr_guests, :total_price, :status)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([':logement_id' => $this->logementId,':voyageur_id' => $this->voyageurId,':start_date' => $this->startDate->format('Y-m-d'),':end_date' => $this->endDate->format('Y-m-d'),':nbr_guests' => $this->nbrGuests,':total_price' => $this->totalPrice,':status' => $this->status->value]);

            if ($result) {
                $this->id = (int) $this->db->lastInsertId();
                $this->notifyHote();
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Reservation save error: " . $e->getMessage());
            throw new Exception("Error creating reservation.");
        }
    }

    private function checkAvailability(): bool {
        try {
            $sql = "SELECT COUNT(*) as count FROM reservation 
                    WHERE logement_id = :logement_id 
                    AND status NOT IN ('cancelled', 'completed')
                    AND (
                        (start_date <= :end_date AND end_date >= :start_date)
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':logement_id' => $this->logementId,':start_date' => $this->startDate->format('Y-m-d'),':end_date' => $this->endDate->format('Y-m-d')]);
            
            $result = $stmt->fetch();
            return $result['count'] == 0;
        } catch (PDOException $e) {
            error_log("Reservation checkAvailability error: " . $e->getMessage());
            return false;
        }
    }

    private function notifyHote(): void {
        try {
            $sql = "SELECT u.email FROM logement l 
                    JOIN users u ON l.hote_id = u.user_id 
                    WHERE l.logement_id = :logement_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':logement_id' => $this->logementId]);
            $hote = $stmt->fetch();

            if ($hote) {
                $notificationSql = "INSERT INTO notifications (user_id, type, title, message) VALUES (:user_id, 'reservation', 'Nouvelle réservation', :message)";
                
                $notificationStmt = $this->db->prepare($notificationSql);
                $message = "New booking for your Logement #{$this->logementId}";
                $notificationStmt->execute([':user_id' => $this->getHoteId(),':message' => $message]);

                // Ici vous pourriez aussi envoyer un email 
            }
        } catch (PDOException $e) {
            error_log("Reservation notifyHote error: " . $e->getMessage());
        }
    }

    public function cancel(int $userId, string $reason = ""): bool {
        try {
            $sql = "UPDATE reservation 
                    SET status = 'cancelled', cancel_reason = :reason, cancel_user_id = :cancel_user_id 
                    WHERE reservation_id = :reservation_id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':reservation_id' => $this->id,':reason' => $reason,':cancel_user_id' => $userId]);
        } catch (PDOException $e) {
            error_log("Reservation cancel error: " . $e->getMessage());
            return false;
        }
    }

    public static function getByVoyageur(int $voyageurId): array {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT r.*, l.title, l.imageUrl, u.first_name as hote_first_name, u.last_name as hote_last_name 
                    FROM reservation r 
                    JOIN logement l ON r.logement_id = l.logement_id 
                    JOIN users u ON l.hote_id = u.user_id 
                    WHERE r.voyageur_id = :voyageur_id 
                    ORDER BY r.created_at DESC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':voyageur_id' => $voyageurId]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Reservation getByVoyageur error: " . $e->getMessage());
            return [];
        }
    }

    private function getHoteId(): int {
        $sql = "SELECT hote_id FROM logement WHERE logement_id = :logement_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':logement_id' => $this->logementId]);
        $result = $stmt->fetch();
        return $result['hote_id'];
    }

    public function getId(): int { return $this->id; }
    public function getTotalPrice(): float { return $this->totalPrice; }
    public function getStatus(): ReservationStatus { return $this->status; }
}
?>