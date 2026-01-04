CREATE DATABASE IF NOT EXISTS kari;
USE kari;

CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(180) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    location VARCHAR(50) NOT NULL,
    role ENUM('voyageur', 'hote', 'admin') DEFAULT 'voyageur',
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE logement (
    logement_id INT PRIMARY KEY AUTO_INCREMENT,
    hote_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    imageUrl VARCHAR(255) NOT NULL,
    type ENUM('apartment', 'house', 'villa', 'other') DEFAULT 'apartment',
    price DECIMAL(10, 2) NOT NULL,
    capacity INT NOT NULL DEFAULT 1,
    average_rating DECIMAL(3, 2) DEFAULT 0.00,
    available_from DATETIME NOT NULL,
    available_to DATETIME NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_logement_hote FOREIGN KEY (hote_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE reservation (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    logement_id INT NOT NULL,
    voyageur_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    nbr_guests INT NOT NULL DEFAULT 1,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    cancel_reason TEXT,
    cancel_user_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reservation_logement FOREIGN KEY (logement_id) REFERENCES logement(logement_id) ON DELETE CASCADE,
    CONSTRAINT fk_reservation_voyageur FOREIGN KEY (voyageur_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_reservation_cancel_by FOREIGN KEY (cancel_user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    CONSTRAINT chk_date CHECK (end_date > start_date)
);

CREATE TABLE review (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    logement_id INT NOT NULL,
    voyageur_id INT NOT NULL,
    reservation_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    is_visible BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_review_logement FOREIGN KEY (logement_id) REFERENCES logement(logement_id) ON DELETE CASCADE,
    CONSTRAINT fk_review_voyageur FOREIGN KEY (voyageur_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_review_reservation FOREIGN KEY (reservation_id) REFERENCES reservation(reservation_id) ON DELETE CASCADE,
    CONSTRAINT unique_reservation_review UNIQUE (reservation_id)
);

CREATE TABLE favorites (
    favorite_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    logement_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_favorite_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_favorite_logement FOREIGN KEY (logement_id) REFERENCES logement(logement_id) ON DELETE CASCADE,
    CONSTRAINT unique_user_logement UNIQUE (user_id, logement_id)
);

CREATE TABLE profile (
    profile_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    photo VARCHAR(255),
    username VARCHAR(50) NOT NULL,
    bio VARCHAR(200),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_profile_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type ENUM('reservation', 'cancellation', 'confirmation', 'review', 'other') NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    email_sent BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notification_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE admin_stats (
    stat_id INT PRIMARY KEY AUTO_INCREMENT,
    total_users INT DEFAULT 0,
    total_logements INT DEFAULT 0,
    total_reservations INT DEFAULT 0,
    total_revenue DECIMAL(15, 2) DEFAULT 0.00,
    period_date DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NOT NULL,
    action_type VARCHAR(100) NOT NULL,
    target_type ENUM('user', 'logement', 'reservation', 'review') NOT NULL,
    target_id INT NOT NULL,
    details TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_admin_logs_user FOREIGN KEY (admin_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- admin (mot de passe: admin123)
INSERT INTO users (first_name, last_name, email, phone, location, password, role) VALUES ('hanane', 'taouili', 'hanan2122hanan@gmail.com', '0609976685', 'safi maroc', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');