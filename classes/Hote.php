<?php
require_once 'User.php';

class Hote extends User {
    private int $userId;

    public function __construct($firstName, $lastName, $email, $phone, $location, $password) {
        parent::__construct($firstName, $lastName, $email, $phone, $location, $password, RoleUs::HOST);
        $this->userId = $this->signup();
    }

    public function getHoteId(): int {
        return $this->userId;
    }
}
?>