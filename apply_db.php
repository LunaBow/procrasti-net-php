<?php
$cfg = include '.env.php';
$dbCfg = $cfg['db'];
$dsn = "mysql:host={$dbCfg['host']};port={$dbCfg['port']};dbname={$dbCfg['name']};charset={$dbCfg['charset']}";
try {
    $pdo = new PDO($dsn, $dbCfg['user'], $dbCfg['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $sql = "SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `display_name` varchar(120) NOT NULL,
  `handle` varchar(80) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT IGNORE INTO `users` (`id`, `display_name`, `email`, `password_hash`) VALUES
(2, 'LunaBow', 'lunamoser1337@gmail.com', '$2b$10$NeqPwXYIH3uLGhae8uvl3.AGSiQGyz0lO0gx.Hf7TTsbFVD0Zy1mq');
CREATE TABLE IF NOT EXISTS `user_settings` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `allow_gamification` tinyint(1) NOT NULL DEFAULT 1,
  `privacy_mode` tinyint(1) NOT NULL DEFAULT 0,
  `sarcastic_comments` tinyint(1) NOT NULL DEFAULT 0,
  `hand_drawn_mode` tinyint(1) NOT NULL DEFAULT 0,
  `leet_speak` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_settings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `color_code` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT IGNORE INTO `categories` (`id`, `name`, `description`, `color_code`) VALUES
(1, 'Focus', 'Aufmerksamkeit, Starten, Ablenkungen reduzieren', '#4a5d50'),
(2, 'Planning', 'Planung, Prioritäten, Struktur', '#d68f7a'),
(3, 'Regulation', 'Emotionsregulation, Nervensystem, Stress', '#2d3e33'),
(4, 'Energy', 'Aktivierung, Antrieb, Körper in Gang bringen', '#e6d5c3'),
(5, 'Survival', 'Low energy, Minimum viable day, Notfallmodus', '#6b7a71'),
(6, 'Social', 'Kommunikation, Grenzen, Hilfe holen', '#8fa395'),
(7, 'Mindset', 'Kognition, Reframing, Selbstmitgefühl', '#c47d68'),
(8, 'ÖH', 'Österreichische Hochschüler_innenschaft tasks', '#ff9900'),
(9, 'School', 'Uni, Lernen, Prüfungen', '#3366cc'),
(10, 'Life', 'Life Admin, Haushalt, Orga', '#99cc33'),
(11, 'Work', 'Job, Hustle, Money', '#cc3300');
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('todo','doing','done','blocked','dropped') DEFAULT 'todo',
  `energy_required` enum('low','medium','high') DEFAULT 'medium',
  `estimated_minutes` smallint(5) UNSIGNED DEFAULT NULL,
  `due_at` datetime DEFAULT NULL,
  `reminder_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_tasks_category_constraint` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_tasks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `habits` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_habits_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `habit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `habit_id` bigint(20) UNSIGNED NOT NULL,
  `done_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_habit_date` (`habit_id`, `done_date`),
  CONSTRAINT `fk_habit_logs_habit` FOREIGN KEY (`habit_id`) REFERENCES `habits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `skills` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(160) NOT NULL,
  `description` text DEFAULT NULL,
  `difficulty_level` tinyint(3) UNSIGNED DEFAULT 1,
  `energy_required` enum('low','medium','high') DEFAULT 'low',
  `time_investment_type` enum('micro','short','deep') DEFAULT 'micro',
  `evidence_level` enum('none','anecdotal','clinical_practice','evidence_based') DEFAULT 'none',
  `source_type` enum('CBT','DBT','habit_theory','mindfulness','somatic','custom','other') DEFAULT 'custom',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_skills_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SET FOREIGN_KEY_CHECKS = 1;";
    $pdo->exec($sql);
    echo "Success";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
