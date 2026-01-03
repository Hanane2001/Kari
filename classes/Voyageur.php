<?php
require_once 'User.php';

class Voyageur extends User {
    private int $userId;

    public function __construct($firstName, $lastName, $email, $phone, $location, $password) {
        parent::__construct($firstName, $lastName, $email, $phone, $location, $password, RoleUs::VOYAGEUR);
        $this->userId = $this->signup();
    }

    public function getVoyageurId(): int {
        return $this->userId;
    }
}
?>