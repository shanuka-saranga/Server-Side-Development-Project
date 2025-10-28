<<<<<<< HEAD
CREATE DATABASE IF NOT EXISTS FESTORA;


USE FESTORA;



CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin_pwd';

GRANT ALL PRIVILEGES ON FESTORA.* TO 'admin'@'localhost';
FLUSH PRIVILEGES;



CREATE TABLE users (
=======
-- NOTICE : DONT change the oder of the tables, Dont change PK & FK.
drop database if exists FESTORA;
drop user if exists 'admin'@'localhost';
CREATE DATABASE FESTORA;
USE FESTORA;

-- Shanuka creates. Methum uses it
CREATE TABLE user (
>>>>>>> 2b25757a0678747b303e36849e490d4b90680f92
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  -- hashed password
    phone VARCHAR(20),
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tharusha
CREATE TABLE organizer (
    organizer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20)
);

-- Samadi
CREATE TABLE event (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    start_date DATETIME,
    end_date DATETIME,
    organizer_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES organizer(organizer_id) ON DELETE SET NULL
);

-- Isuru
CREATE TABLE booking (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    tickets INT DEFAULT 1,
    payment_status ENUM('pending','paid','cancelled') DEFAULT 'pending',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES event(event_id) ON DELETE CASCADE
);

-- Imashi
CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_method ENUM('card','paypal','other') DEFAULT 'card',
    status ENUM('success','failed','pending') DEFAULT 'pending',
    FOREIGN KEY (booking_id) REFERENCES booking(booking_id) ON DELETE CASCADE
);

-- Banti
CREATE TABLE review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES event(event_id) ON DELETE CASCADE
);

-- Tharinda
CREATE TABLE appointment (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    fname VARCHAR(255),
    reason VARCHAR(100),
    date DATE,
    contact CHAR(10),
    branch VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE SET NULL
);

-- Admin user
CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin_pwd';
GRANT ALL PRIVILEGES ON FESTORA.* TO 'admin'@'localhost';
FLUSH PRIVILEGES;
