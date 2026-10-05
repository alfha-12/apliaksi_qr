-- =============================================
-- Barcode Review System - Database Schema
-- =============================================

CREATE DATABASE IF NOT EXISTS barcode_review
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE barcode_review;

-- Tabel Admin
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Tempat (Places)
CREATE TABLE IF NOT EXISTS places (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT NULL,
    address VARCHAR(500) NULL,
    category VARCHAR(50) DEFAULT 'umum',
    barcode_code VARCHAR(20) NOT NULL UNIQUE,
    image VARCHAR(255) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE,
    INDEX idx_admin (admin_id),
    INDEX idx_barcode (barcode_code),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Tabel Ulasan (Reviews)
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    place_id INT NOT NULL,
    reviewer_name VARCHAR(100) DEFAULT 'Anonim',
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NOT NULL,
    is_approved TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE,
    INDEX idx_place (place_id),
    INDEX idx_approved (is_approved)
) ENGINE=InnoDB;

-- Insert Admin Default
-- Username: admin
-- Password: admin123
INSERT INTO admins (username, password, name) VALUES
('admin', '$2y$10$0Hqo49UvKgT.IYeVtlP7XehmyDfDA1M2n2KJmgzfpR7QkxTVhRM5K', 'Administrator');

-- Sample Places
INSERT INTO places (admin_id, name, description, address, category, barcode_code, status) VALUES
(1, 'Warung Makan Sederhana', 'Warung makan dengan menu tradisional Indonesia. Terkenal dengan ayam goreng dan lalapan segar.', 'Jl. Merdeka No. 45, Jakarta Pusat', 'restoran', 'WRG001ABC', 'active'),
(1, 'Cafe Kopi Senja', 'Cafe cozy dengan suasana nyaman untuk bekerja atau bersantai. Menyajikan kopi specialty dan pastry.', 'Jl. Sudirman No. 12, Bandung', 'kafe', 'CFE002XYZ', 'active'),
(1, 'Taman Kota Hijau', 'Taman rekreasi keluarga dengan area bermain anak, jogging track, dan spot foto menarik.', 'Jl. Raya Bogor Km 5, Depok', 'wisata', 'TMN003DEF', 'active');

-- Sample Reviews (approved)
INSERT INTO reviews (place_id, reviewer_name, rating, comment, is_approved) VALUES
(1, 'Budi Santoso', 5, 'Ayam gorengnya enak banget! Porsinya besar dan harganya terjangkau. Recommended!', 1),
(1, 'Siti Aminah', 4, 'Makanannya enak, tapi tempatnya agak ramai saat jam makan siang.', 1),
(2, 'Andi Wijaya', 5, 'Kopinya sangat nikmat. Tempatnya nyaman untuk nugas. WiFi kencang!', 1),
(2, 'Rina Kartika', 3, 'Kopi oke, tapi harga agak mahal untuk ukuran student.', 1),
(3, 'Dedi Kurniawan', 5, 'Tempatnya asri dan bersih. Cocok untuk keluarga. Anak-anak senang bermain di sini.', 1);

-- Sample Pending Reviews
INSERT INTO reviews (place_id, reviewer_name, rating, comment, is_approved) VALUES
(1, 'Joko Susilo', 2, 'Pelayanannya kurang ramah hari ini. Makanan lama datang.', 0),
(3, 'Maya Putri', 4, 'Bagus tapi kurang fasilitas toilet yang bersih.', 0);
