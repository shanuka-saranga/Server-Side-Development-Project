-- Drop and create the database
DROP DATABASE IF EXISTS festora_db;
CREATE DATABASE festora_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE festora_db;

-- ------------------------------------------------------------
-- USERS TABLE
-- ------------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- ADMIN TABLE
-- ------------------------------------------------------------
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- TEAM SELECTIONS (Organizers)
-- ------------------------------------------------------------
CREATE TABLE team_selections (
    organizer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    coordinator VARCHAR(100) DEFAULT NULL,
    creative VARCHAR(255) DEFAULT NULL,
    technical VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- BOOKING TABLE
-- ------------------------------------------------------------
CREATE TABLE booking (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    location VARCHAR(50) NOT NULL,
    guest_count INT NOT NULL,
    event_start DATETIME NOT NULL,
    event_end DATETIME NOT NULL,
    event_description TEXT,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX (user_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- PAYMENT TABLE
-- ------------------------------------------------------------
CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    status ENUM('success', 'failed', 'pending') DEFAULT 'pending',
    note VARCHAR(300)
) ENGINE=InnoDB;

ALTER TABLE payment
ADD COLUMN package VARCHAR(50) NOT NULL AFTER note;


-- ------------------------------------------------------------
-- REVIEW TABLE
-- ------------------------------------------------------------
CREATE TABLE review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rating INT NOT NULL,
    comment TEXT NOT NULL,
    recommend ENUM('yes', 'no') NOT NULL,
    event_name VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    user_ip VARCHAR(45) NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX (user_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- APPOINTMENT TABLE
-- ------------------------------------------------------------
CREATE TABLE appointment (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(255),
    reason VARCHAR(100),
    date DATE,
    contact CHAR(10),
    branch VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- CONTACT TABLE
-- ------------------------------------------------------------
CREATE TABLE contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    telephone VARCHAR(15) NOT NULL,
    email VARCHAR(50) NOT NULL,
    message_t VARCHAR(255)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- INDEXES FOR PERFORMANCE
-- ------------------------------------------------------------
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_booking_user ON booking(user_id);
CREATE INDEX idx_review_user ON review(user_id);
