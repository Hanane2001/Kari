<?php
require_once __DIR__ . '/../config/Database.php';

enum RoleUs: string {
    case VOYAGEUR = 'voyageur';
    case HOTE = 'hote';
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
    protected PDO $db;

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

    public function signup(): int|false {
        try {
            $sql = "INSERT INTO users (first_name, last_name, email, phone, location, password, role) VALUES (:first_name, :last_name, :email, :phone, :location, :password, :role)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':first_name' => $this->firstName,':last_name' => $this->lastName,':email' => $this->email,':phone' => $this->phone,':location' => $this->location,':password' => $this->password,':role' => $this->role->value]);
            
            $this->id = (int) $this->db->lastInsertId();
            return $this->id;
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }

    public static function login(string $email, string $password): array|false {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_location'] = $user['location'];
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    public static function logout(): bool {
        session_destroy();
        session_unset();
        return true;
    }

    public static function getById(int $userId): ?array {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT * FROM users WHERE user_id = :user_id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            
            $user = $stmt->fetch();
            return $user ? $user : null;
        } catch (PDOException $e) {
            error_log("User getById error: " . $e->getMessage());
            return null;
        }
    }

    public static function isActive(int $userId): bool | null {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT * FROM users WHERE user_id = :user_id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            
            $user = $stmt->fetch();
            if($user['is_active'] === 0){
                return false;
            }
            return true;
        } catch (PDOException $e) {
            error_log("User getById error: " . $e->getMessage());
            return null;
        }
    }

    public static function updateProfile(int $userId, array $data): bool {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, phone = :phone, location = :location WHERE user_id = :user_id";
            $stmt = $db->prepare($sql);
            return $stmt->execute([':first_name' => $data['first_name'],':last_name' => $data['last_name'],':phone' => $data['phone'],':location' => $data['location'],':user_id' => $userId]);
        } catch (PDOException $e) {
            error_log("User updateProfile error: " . $e->getMessage());
            return false;
        }
    }

    public function getId(): int { 
        return $this->id; 
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function getRole(): RoleUs {
        return $this->role;
    }

    public function getFullName(): string {
        return $this->firstName . ' ' . $this->lastName;
    }
}
?>