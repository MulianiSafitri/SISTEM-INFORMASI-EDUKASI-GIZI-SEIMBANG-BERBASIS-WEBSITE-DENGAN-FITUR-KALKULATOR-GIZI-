-- Database: nutricalc

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

-- Table structure for table `admins`
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin: admin / password123
INSERT INTO `admins` (`username`, `password_hash`, `email`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@nutricalc.com');

-- --------------------------------------------------------

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT 'male',
  `birthdate` date DEFAULT NULL,
  `height_cm` float DEFAULT NULL,
  `weight_kg` float DEFAULT NULL,
  `activity_level` enum('sedentary','light','moderate','active','very_active') DEFAULT 'sedentary',
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table structure for table `categories`
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`name`, `slug`, `description`) VALUES
('Karbohidrat', 'karbohidrat', 'Sumber energi utama'),
('Protein', 'protein', 'Zat pembangun tubuh'),
('Sayur', 'sayur', 'Sumber serat dan vitamin'),
('Buah', 'buah', 'Sumber vitamin alami'),
('Camilan', 'camilan', 'Makanan ringan'),
('Minuman', 'minuman', 'Pelepas dahaga'),
('Makanan Tradisional', 'makanan-tradisional', 'Kuliner nusantara');

-- --------------------------------------------------------

-- Table structure for table `foods`
CREATE TABLE `foods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `portion_gram` float DEFAULT 100,
  `calories_kcal` float DEFAULT 0,
  `protein_g` float DEFAULT 0,
  `carbs_g` float DEFAULT 0,
  `fat_g` float DEFAULT 0,
  `fiber_g` float DEFAULT 0,
  `sodium_mg` float DEFAULT 0,
  `ingredients` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Data
INSERT INTO `foods` (`category_id`, `name`, `description`, `portion_gram`, `calories_kcal`, `protein_g`, `carbs_g`, `fat_g`, `fiber_g`, `sodium_mg`, `ingredients`, `image`) VALUES
(7, 'Ulos (Kue Tradisional)', 'Kue khas dengan tekstur lembut, manis.', 100, 250, 3, 40, 8, 1, 50, 'tepung beras, gula merah, kelapa', 'ulos.jpg'),
(1, 'Nasi Putih', 'Nasi putih pulen hangat.', 100, 130, 2.7, 28, 0.3, 0.4, 1, 'beras, air', 'nasi_putih.jpg'),
(2, 'Dada Ayam Bakar', 'Dada ayam tanpa kulit dibakar.', 100, 165, 31, 0, 3.6, 0, 74, 'dada ayam, bumbu rempah', 'ayam_bakar.jpg'),
(2, 'Telur Rebus', 'Telur ayam direbus matang.', 50, 78, 6, 0.6, 5, 0, 62, 'telur ayam', 'telur_rebus.jpg'),
(3, 'Sayur Bayam Bening', 'Sup bayam segar dengan jagung.', 100, 35, 2, 5, 0.5, 2.5, 150, 'bayam, jagung, air', 'sayur_bayam.jpg'),
(4, 'Pisang Ambon', 'Buah pisang segar.', 100, 89, 1.1, 22.8, 0.3, 2.6, 1, 'pisang', 'pisang.jpg'),
(2, 'Tempe Goreng', 'Tempe kedelai digoreng.', 50, 100, 9, 5, 7, 2, 10, 'tempe, minyak goreng', 'tempe.jpg'),
(1, 'Roti Gandum', 'Roti dari tepung gandum utuh.', 40, 100, 4, 18, 1.5, 3, 150, 'tepung gandum, ragi', 'roti_gandum.jpg'),
(5, 'Keripik Singkong', 'Camilan renyah dari singkong.', 50, 250, 1, 35, 12, 2, 200, 'singkong, minyak, garam', 'keripik.jpg'),
(6, 'Jus Alpukat', 'Jus alpukat dengan sedikit susu.', 200, 220, 3, 15, 18, 5, 10, 'alpukat, susu kental manis, air', 'jus_alpukat.jpg');

-- --------------------------------------------------------

-- Table structure for table `food_images`
CREATE TABLE `food_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `food_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `food_id` (`food_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table structure for table `food_nutrients` (Optional micro nutrients)
CREATE TABLE `food_nutrients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `food_id` int(11) NOT NULL,
  `nutrient_name` varchar(50) NOT NULL,
  `amount` float NOT NULL,
  `unit` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `food_id` (`food_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table structure for table `recipes`
CREATE TABLE `recipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `servings` int(11) DEFAULT 1,
  `instructions` text DEFAULT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table structure for table `recipe_items`
CREATE TABLE `recipe_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipe_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `amount_gram` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `recipe_id` (`recipe_id`),
  KEY `food_id` (`food_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table structure for table `mealplans`
CREATE TABLE `mealplans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `total_calories` float DEFAULT 0,
  `total_protein` float DEFAULT 0,
  `total_carbs` float DEFAULT 0,
  `total_fat` float DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table structure for table `mealplan_items`
CREATE TABLE `mealplan_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mealplan_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `qty_portion` float DEFAULT 1,
  `gram_per_portion` float DEFAULT 100,
  PRIMARY KEY (`id`),
  KEY `mealplan_id` (`mealplan_id`),
  KEY `food_id` (`food_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table structure for table `tfidf_cache`
CREATE TABLE `tfidf_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `food_id` int(11) NOT NULL,
  `tfidf_vector` text DEFAULT NULL,
  `updated_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `food_id` (`food_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;

-- Table for Online Consultations
CREATE TABLE IF NOT EXISTS consultations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    question TEXT NOT NULL,
    answer TEXT DEFAULT NULL,
    status ENUM('pending', 'answered') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    answered_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
