<?php
require_once 'User.php';

class Admin extends User {
    public function __construct($firstName, $lastName, $email, $phone, $location, $password) {
        parent::__construct($firstName, $lastName, $email, $phone, $location, $password, RoleUs::ADMIN);
        $this->signup();
    }
}