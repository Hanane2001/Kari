<?php
require_once __DIR__ . '/../config/Database.php';

enum RoleUs: string {
    case VOYAGEUR = 'voyageur';
    case HOST = 'host';
    case ADMIN = 'admin';
}

class User {
    protected int $id;
    protected string $firstName;
    protected string $lastName;
    protected string $email;
    protected string $phone;
    protected string $location;
    protected string $password;
    protected RoleUs $role;
    protected Database $db;

    public function __construct(string $firstName, string $lastName, string $email, string $phone, string $location, string $password, RoleUs $role) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->location = $location;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->role = $role;
        $this->db = Database::getInstance()->getConnection();
    }

    public function signup(){
        try {
            $sql = "INSERT INTO users (firstName, lastName, email, phone, location, password, role) VALUES (:firstName, :lastName, :email, :phone, :location, :password, :role)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':firstName' => $this->firstName,':lastName' => $this->lastName,':email' => $this->email,':phone' => $this->phone,':location' => $this->location,':password' => $this->password,':role' => $this->role->value]);
            
            $this->id = $this->db->lastInsertId();
            return true;
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }

    public static function login($email, $password){
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['firstName'] . ' ' . $user['lastName'];
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Connection error: " . $e->getMessage());
            return false;
        }
    }

    public static function logout(){
        session_destroy();
        session_unset();
        return true;
    }

    public function getId() { return $this->id; }
    public function getFirstName() { return $this->firstName; }
    public function getLastName() { return $this->lastName; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getLocation() { return $this->location; }
    public function getRole() { return $this->role; }
    public function getFullName() { return $this->firstName . ' ' . $this->lastName;}
}
?>