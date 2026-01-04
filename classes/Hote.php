<?php
require_once 'User.php';

class Hote extends User {
    private ?int $userId = null;

    public function __construct($firstName, $lastName, $email, $phone, $location, $password) {
        parent::__construct($firstName, $lastName, $email, $phone, $location, $password, RoleUs::HOTE);
        $this->userId = $this->signup();
    }

    public function getHoteId(): ?int {
        return $this->userId;
    }
}
?>