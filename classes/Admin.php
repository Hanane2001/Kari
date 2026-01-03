<?php
require_once 'User.php';

class Admin extends User {
    private int $userId;

    public function __construct($firstName, $lastName, $email, $phone, $location, $password) {
        parent::__construct($firstName, $lastName, $email, $phone, $location, $password, RoleUs::ADMIN);
        $this->userId = $this->signup();
    }

    public function getAdminId(): int {
        return $this->userId;
    }
}
?>