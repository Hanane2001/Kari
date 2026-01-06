<?php
require_once __DIR__ . '/../config/Database.php';

enum LogementType: string {
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case VILLA = 'villa';
    case OTHER = 'other';
}

class Logement {
    private int $id;
    private int $hoteId;
    private string $title;
    private string $description;
    private string $imageUrl;
    private LogementType $type;
    private float $price;
    private int $capacity;
    private float $averageRating;
    private DateTime $availableFrom;
    private DateTime $availableTo;
    private bool $isActive;
    private PDO $db;

    public function __construct(int $hoteId, string $title, string $description, string $imageUrl, LogementType $type, float $price, int $capacity, DateTime $availableFrom, DateTime $availableTo) {
        $this->hoteId = $hoteId;
        $this->title = $title;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->type = $type;
        $this->price = $price;
        $this->capacity = $capacity;
        $this->averageRating = 0.0;
        $this->availableFrom = $availableFrom;
        $this->availableTo = $availableTo;
        $this->isActive = true;
        $this->db = Database::getInstance()->getConnection();
    }

    public function save(): bool {
        try {
            $sql = "INSERT INTO logement (hote_id, title, description, imageUrl, type, price, capacity, available_from, available_to, is_active) VALUES (:hote_id, :title, :description, :imageUrl, :type, :price, :capacity, :available_from, :available_to, :is_active)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':hote_id' => $this->hoteId,':title' => $this->title,':description' => $this->description,':imageUrl' => $this->imageUrl,':type' => $this->type->value,':price' => $this->price,':capacity' => $this->capacity,':available_from' => $this->availableFrom->format('Y-m-d H:i:s'),':available_to' => $this->availableTo->format('Y-m-d H:i:s'),':is_active' => $this->isActive]);
        } catch (PDOException $e) {
            error_log("Logement save error: " . $e->getMessage());
            return false;
        }
    }

    public static function getAllAvailable(?string $city = null, ?float $minPrice = null, ?float $maxPrice = null, ?DateTime $startDate = null, ?DateTime $endDate = null, ?int $guests = null, int $limit = 10, int $offset = 0): array {
        try {
            $db = Database::getInstance()->getConnection();
            
            $sql = "SELECT l.*, u.first_name, u.last_name 
                    FROM logement l 
                    JOIN users u ON l.hote_id = u.user_id 
                    WHERE l.is_active = TRUE 
                    AND l.available_from <= NOW() 
                    AND l.available_to >= NOW()";
            
            $params = [];
            
            if ($city) {
                $sql .= " AND u.location LIKE :city";
                $params[':city'] = "%$city%";
            }
            
            if ($minPrice !== null) {
                $sql .= " AND l.price >= :min_price";
                $params[':min_price'] = $minPrice;
            }
            
            if ($maxPrice !== null) {
                $sql .= " AND l.price <= :max_price";
                $params[':max_price'] = $maxPrice;
            }
            
            if ($startDate) {
                $sql .= " AND l.available_from <= :start_date";
                $params[':start_date'] = $startDate->format('Y-m-d H:i:s');
            }
            
            if ($endDate) {
                $sql .= " AND l.available_to >= :end_date";
                $params[':end_date'] = $endDate->format('Y-m-d H:i:s');
            }
            
            if ($guests) {
                $sql .= " AND l.capacity >= :capacity";
                $params[':capacity'] = $guests;
            }
            
            $sql .= " ORDER BY l.created_at DESC LIMIT :limit OFFSET :offset";
            $params[':limit'] = $limit;
            $params[':offset'] = $offset;
            
            $stmt = $db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Logement getAllAvailable error: " . $e->getMessage());
            return [];
        }
    }

    public static function getById(int $logementId): ?array {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT l.*, u.first_name, u.last_name, u.phone, u.email 
                    FROM logement l 
                    JOIN users u ON l.hote_id = u.user_id 
                    WHERE l.logement_id = :logement_id AND l.is_active = TRUE";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':logement_id' => $logementId]);
            
            $result = $stmt->fetch();
            return $result ? $result : null;
        } catch (PDOException $e) {
            error_log("Logement getById error: " . $e->getMessage());
            return null;
        }
    }

    public function update(int $logementId, int $hoteId): bool{
        try {
            $db = Database::getInstance()->getConnection();

            $sql = "UPDATE logement 
                    SET title = :title,
                        description = :description,
                        imageUrl = :imageUrl,
                        type = :type,
                        price = :price,
                        capacity = :capacity,
                        available_from = :available_from,
                        available_to = :available_to
                    WHERE logement_id = :logement_id 
                    AND hote_id = :hote_id";

            $stmt = $db->prepare($sql);

            return $stmt->execute([':title'=> $this->title,':description'=> $this->description,':imageUrl'=> $this->imageUrl,':type'=> $this->type->value,':price'=> $this->price,':capacity'=> $this->capacity,':available_from'=> $this->availableFrom->format('Y-m-d'),':available_to'=> $this->availableTo->format('Y-m-d'),':logement_id'=> $logementId,':hote_id'=> $hoteId]);
        } catch (PDOException $e) {
            error_log("Error updating logement: " . $e->getMessage());
            return false;
        }
    }

    public static function delete(int $logementId, int $hoteId): bool{
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM logement WHERE logement_id = :id AND hote_id = :hote_id");

        return $stmt->execute([':id' => $logementId,':hote_id' => $hoteId]);
    }



    public static function getByHote(int $hoteId): array {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT * FROM logement WHERE hote_id = :hote_id ORDER BY created_at DESC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':hote_id' => $hoteId]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Logement getByHote error: " . $e->getMessage());
            return [];
        }
    }

    public function updateAverageRating(): bool {
        try {
            $sql = "UPDATE logement SET average_rating = (
                        SELECT COALESCE(AVG(rating), 0) FROM review 
                        WHERE logement_id = :logement_id AND is_visible = TRUE
                    ) WHERE logement_id = :logement_id2";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':logement_id' => $this->id,':logement_id2' => $this->id]);
        } catch (PDOException $e) {
            error_log("Logement updateAverageRating error: " . $e->getMessage());
            return false;
        }
    }

    public function getId(): int { return $this->id; }
    public function getHoteId(): int { return $this->hoteId; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getPrice(): float { return $this->price; }
    public function getCapacity(): int { return $this->capacity; }
    public function getAverageRating(): float { return $this->averageRating; }
}
?>