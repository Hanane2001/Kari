<?php
require_once 'User.php';

class Voyageur extends User {
    private int $userId;

    public function __construct($firstName, $lastName, $email, $phone, $location, $password) {
        parent::__construct($firstName, $lastName, $email, $phone, $location, $password, RoleUs::VOYAGEUR);
    }

    public function signup(){
        $result = parent::signup();
        
        if ($result && $this->id) {
            try {
                $sql = "INSERT INTO voyageur (user_id) VALUES (:user_id)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':user_id' => $this->id]);
                $this->userId = $this->id;
                return true;
            } catch (PDOException $e) {
                error_log("Traveler creation error: " . $e->getMessage());
                return false;
            }
        }
        return $result;
    }
    public function getVoyageurId(): int {
        return $this->userId;
    }

    public function reserverLogement($logementId, $dateDebut, $dateFin) {
        echo "Reservation made by " . $this->getFullName();
    }
}
?>