<?php
require_once 'User.php';

class Hote extends User {
    private int $userId;

    public function __construct($firstName, $lastName, $email, $phone, $location, $password) {
        parent::__construct($firstName, $lastName, $email, $phone, $location, $password, RoleUs::HOST);
    }

    public function signup(){
        $result = parent::signup();
        if ($result && $this->id) {
            try {
                $sql = "INSERT INTO hote (user_id) VALUES (:user_id)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':user_id' => $this->id]);
                $this->userId = $this->id;
                return true;
            } catch (PDOException $e) {
                error_log("Host creation error: " . $e->getMessage());
                return false;
            }
        }
        return $result;
    }

    public function getHoteId(): int {
        return $this->userId;
    }

    public function ajouterLogement($titre, $description, $prix) {
        echo "Rentals added by" . $this->getFullName();
    }
}
?>