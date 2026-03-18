-- Complete Database Schema for Procrasti-net with Extended Task Features
-- This includes categories, due dates, reminders, and all functionality
-- Run this in phpMyAdmin after dropping the existing database

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- 1. USERS & SETTINGS
-- --------------------------------------------------------

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

INSERT IGNORE INTO `user_settings` (`user_id`, `allow_gamification`, `privacy_mode`, `sarcastic_comments`, `hand_drawn_mode`, `leet_speak`) VALUES
(2, 0, 0, 0, 0, 0);

-- --------------------------------------------------------
-- 2. CATEGORIES (Upgraded with your new tags)
-- --------------------------------------------------------

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

-- --------------------------------------------------------
-- 3. TASKS (Upgraded with due_at & reminder_at)
-- --------------------------------------------------------

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
  KEY `fk_tasks_category` (`category_id`),
  CONSTRAINT `fk_tasks_category_constraint` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_tasks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `task_chunks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `notes` text DEFAULT NULL,
  `estimated_minutes` smallint(5) UNSIGNED DEFAULT NULL,
  `status` enum('todo','doing','done','blocked','dropped') DEFAULT 'todo',
  `sort_order` tinyint(3) UNSIGNED DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_task_chunks_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. HABITS & ROUTINES
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `habits` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_habits_user` (`user_id`),
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

CREATE TABLE IF NOT EXISTS `routines` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `recurrence_type` enum('daily','weekly','custom') DEFAULT 'weekly',
  `recurrence_rule` varchar(120) DEFAULT NULL,
  `target_per_week` tinyint(3) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `weekdays` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`weekdays`)),
  `reminder_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_routines_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `routine_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `routine_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `done_at` datetime DEFAULT current_timestamp(),
  `mood_after` tinyint(3) UNSIGNED DEFAULT NULL,
  `energy_after` tinyint(3) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `done_date` date GENERATED ALWAYS AS (cast(`done_at` as date)) STORED,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_routine_logs_routine` FOREIGN KEY (`routine_id`) REFERENCES `routines` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. SKILLS, EXERCISES & STATES
-- --------------------------------------------------------

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

CREATE TABLE IF NOT EXISTS `exercises` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `skill_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `step_by_step` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`step_by_step`)),
  `duration_minutes` smallint(5) UNSIGNED DEFAULT NULL,
  `requires_material` tinyint(1) DEFAULT 0,
  `indoor_outdoor` enum('indoor','outdoor','either') DEFAULT 'either',
  `intensity` enum('calm','neutral','activating') DEFAULT 'neutral',
  `crisis_safe` tinyint(1) DEFAULT 1,
  `is_guided` tinyint(1) DEFAULT 0,
  `min_age` smallint(5) UNSIGNED DEFAULT NULL,
  `max_age` smallint(5) UNSIGNED DEFAULT NULL,
  `suitability_notes` text DEFAULT NULL,
  `contraindication_notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_exercises_skill_name` (`skill_id`,`name`),
  KEY `idx_exercises_skill` (`skill_id`),
  CONSTRAINT `fk_exercises_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `states` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `states` (`id`, `name`, `description`) VALUES
(1, 'Overwhelmed', 'Zu viel gleichzeitig, Startblockade, Stress hoch'),
(2, 'Shutdown', 'Erschöpfung, Leere, "nichts geht"'),
(3, 'Anxious', 'Anspannung, Grübeln, Nervosität'),
(4, 'Impulsive', 'Drang sofort zu handeln, Risiko/Spontanität'),
(5, 'Low Mood', 'Traurigkeit, Hoffnungslosigkeit, Antrieb niedrig'),
(6, 'Restless', 'Unruhe, ADHS-typisches "ich kann nicht sitzen"'),
(7, 'Rage', 'Wutspikes, Triggered, Reizüberflutung'),
(8, 'Numb', 'Taubheit, Dissoziation-ish, wenig Zugang zu Gefühl'),
(9, 'Rejection Sensitive', 'Krasse Reaktion auf Kritik/Abweisung (RSD)'),
(10, 'Focused', 'Flow/Arbeitsmodus');

CREATE TABLE IF NOT EXISTS `state_exercise_map` (
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `priority` tinyint(3) UNSIGNED DEFAULT 3,
  `why_it_works` text DEFAULT NULL,
  PRIMARY KEY (`state_id`, `exercise_id`),
  CONSTRAINT `fk_map_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_map_exercise` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. LOGS & TRACKING
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `checkins` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `mood` tinyint(3) UNSIGNED NOT NULL,
  `energy` tinyint(3) UNSIGNED NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_checkins_user_date` (`user_id`,`date`),
  CONSTRAINT `fk_checkins_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `exercise_runs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `started_at` datetime DEFAULT current_timestamp(),
  `finished_at` datetime DEFAULT NULL,
  `tension_before` tinyint(3) UNSIGNED DEFAULT NULL,
  `tension_after` tinyint(3) UNSIGNED DEFAULT NULL,
  `energy_before` tinyint(3) UNSIGNED DEFAULT NULL,
  `energy_after` tinyint(3) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_exercise_runs_user_time` (`user_id`,`started_at`),
  KEY `idx_exercise_runs_exercise_time` (`exercise_id`,`started_at`),
  CONSTRAINT `fk_exercise_runs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_exercise_runs_exercise` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `exercise_tags` (
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`exercise_id`,`tag_id`),
  KEY `idx_exercise_tags_tag` (`tag_id`),
  CONSTRAINT `fk_extags_exercise` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_extags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `skill_tags` (
  `skill_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`skill_id`, `tag_id`),
  CONSTRAINT `fk_skilltags_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_skilltags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample tasks with categories, due dates, and reminders
INSERT IGNORE INTO `tasks` (`id`, `user_id`, `category_id`, `title`, `description`, `status`, `energy_required`, `estimated_minutes`, `due_at`, `reminder_at`, `created_at`, `completed_at`, `updated_at`) VALUES
(1, 2, 1, 'Morning meditation', '10 minutes of mindfulness practice', 'todo', 'low', 10, '2026-03-20 00:00:00', '2026-03-20 08:00:00', '2026-03-18 00:00:00', NULL, '2026-03-18 00:00:00'),
(2, 2, 11, 'Finish project report', 'Complete the quarterly report for management', 'doing', 'high', 120, '2026-03-22 00:00:00', '2026-03-21 14:00:00', '2026-03-18 00:00:01', NULL, '2026-03-18 00:00:01'),
(3, 2, 9, 'Study for math exam', 'Review chapters 5-7 for tomorrow\'s test', 'todo', 'medium', 90, '2026-03-19 00:00:00', '2026-03-19 18:00:00', '2026-03-18 00:00:02', NULL, '2026-03-18 00:00:02'),
(4, 2, 6, 'Call mom', 'Weekly check-in with family', 'todo', 'low', 15, NULL, '2026-03-20 19:00:00', '2026-03-18 00:00:03', NULL, '2026-03-18 00:00:03'),
(5, 2, 10, 'Grocery shopping', 'Weekly food and household items', 'todo', 'medium', 60, '2026-03-21 00:00:00', NULL, '2026-03-18 00:00:04', NULL, '2026-03-18 00:00:04'),
(6, 2, 4, 'Gym workout', 'Cardio and strength training session', 'done', 'high', 75, NULL, NULL, '2026-03-17 00:00:00', '2026-03-17 00:00:00', '2026-03-18 00:00:05'),
(7, 2, 11, 'Review budget', 'Monthly expense analysis and savings check', 'todo', 'medium', 30, '2026-03-25 00:00:00', '2026-03-24 20:00:00', '2026-03-18 00:00:06', NULL, '2026-03-18 00:00:06'),
(8, 2, 1, 'Write blog post', 'Draft article about productivity tips', 'todo', 'medium', 45, '2026-03-23 00:00:00', NULL, '2026-03-18 00:00:07', NULL, '2026-03-18 00:00:07'),
(9, 2, 10, 'Clean bathroom', 'Deep clean and organize bathroom supplies', 'todo', 'medium', 45, '2026-03-24 00:00:00', NULL, '2026-03-18 00:00:08', NULL, '2026-03-18 00:00:08'),
(10, 2, 8, 'Book flight tickets', 'Research and book summer vacation flights', 'todo', 'low', 30, '2026-04-01 00:00:00', '2026-03-30 10:00:00', '2026-03-18 00:00:09', NULL, '2026-03-18 00:00:09');

-- Insert comprehensive skills in German and English
INSERT IGNORE INTO `skills` (`id`, `category_id`, `name`, `description`, `difficulty_level`, `energy_required`, `time_investment_type`, `evidence_level`, `source_type`, `created_at`) VALUES
-- Focus Skills (Category 1)
(1, 1, '5-Minute Rule', 'Starte nur 5 Minuten. Danach darfst du aufhören oder weitermachen.', 1, 'low', 'micro', 'anecdotal', 'habit_theory', '2026-03-18 00:00:00'),
(2, 1, '5-Minuten-Regel', 'Starte nur 5 Minuten. Danach darfst du aufhören oder weitermachen.', 1, 'low', 'micro', 'anecdotal', 'habit_theory', '2026-03-18 00:00:01'),
(3, 1, 'Pomodoro 25/5', '25 Minuten Fokus, 5 Minuten Pause. Wiederholen.', 1, 'medium', 'short', 'evidence_based', 'habit_theory', '2026-03-18 00:00:02'),
(4, 1, 'Pomodoro 25/5', '25 minutes focus, 5 minutes break. Repeat.', 1, 'medium', 'short', 'evidence_based', 'habit_theory', '2026-03-18 00:00:03'),
(5, 1, 'Implementation Intention', 'Wenn Situation X eintritt, dann werde ich Y tun.', 2, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-18 00:00:04'),
(6, 1, 'Implementierungsintention', 'Wenn Situation X eintritt, dann werde ich Y tun.', 2, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-18 00:00:05'),
(7, 1, 'Distraction Blocking', 'Blockiere bekannte Ablenkungen für 25 Minuten.', 2, 'medium', 'short', 'anecdotal', 'custom', '2026-03-18 00:00:06'),
(8, 1, 'Ablenkungsblockade', 'Blockiere bekannte Ablenkungen für 25 Minuten.', 2, 'medium', 'short', 'anecdotal', 'custom', '2026-03-18 00:00:07'),

-- Planning Skills (Category 2)
(9, 2, 'Top 3 Priorities', 'Nur 3 Aufgaben zählen heute. Alles andere Bonus.', 1, 'low', 'micro', 'evidence_based', 'habit_theory', '2026-03-18 00:00:08'),
(10, 2, 'Top 3 Prioritäten', 'Nur 3 Aufgaben zählen heute. Alles andere Bonus.', 1, 'low', 'micro', 'evidence_based', 'habit_theory', '2026-03-18 00:00:09'),
(11, 2, 'Weekly Review', 'Wöchentliche Reflexion: Was lief gut? Was verbessern?', 2, 'medium', 'short', 'anecdotal', 'custom', '2026-03-18 00:00:10'),
(12, 2, 'Wöchentliche Reflexion', 'Wöchentliche Reflexion: Was lief gut? Was verbessern?', 2, 'medium', 'short', 'anecdotal', 'custom', '2026-03-18 00:00:11'),
(13, 2, 'Time Boxing', 'Lege feste Zeiten für Aufgaben fest.', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-18 00:00:12'),
(14, 2, 'Zeitblockung', 'Lege feste Zeiten für Aufgaben fest.', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-18 00:00:13'),

-- Regulation Skills (Category 3)
(15, 3, '4-7-8 Breathing', '4 Sekunden einatmen, 7 halten, 8 ausatmen.', 1, 'low', 'micro', 'clinical_practice', 'mindfulness', '2026-03-18 00:00:14'),
(16, 3, '4-7-8 Atmung', '4 Sekunden einatmen, 7 halten, 8 ausatmen.', 1, 'low', 'micro', 'clinical_practice', 'mindfulness', '2026-03-18 00:00:15'),
(17, 3, 'Progressive Muscle Relaxation', 'Anspannen und entspannen von Muskelgruppen.', 2, 'low', 'short', 'evidence_based', 'somatic', '2026-03-18 00:00:16'),
(18, 3, 'Progressive Muskelentspannung', 'Anspannen und entspannen von Muskelgruppen.', 2, 'low', 'short', 'evidence_based', 'somatic', '2026-03-18 00:00:17'),
(19, 3, 'Cold Water Splash', 'Kaltes Wasser ins Gesicht für Reset.', 1, 'low', 'micro', 'anecdotal', 'somatic', '2026-03-18 00:00:18'),
(20, 3, 'Kaltwassersplash', 'Kaltes Wasser ins Gesicht für Reset.', 1, 'low', 'micro', 'anecdotal', 'somatic', '2026-03-18 00:00:19'),

-- Energy Skills (Category 4)
(21, 4, '10-Minute Walk', 'Kurzer Spaziergang an der frischen Luft.', 1, 'low', 'short', 'evidence_based', 'somatic', '2026-03-18 00:00:20'),
(22, 4, '10-Minuten-Spaziergang', 'Kurzer Spaziergang an der frischen Luft.', 1, 'low', 'short', 'evidence_based', 'somatic', '2026-03-18 00:00:21'),
(23, 4, 'Sunlight Exposure', '15 Minuten Sonnenlicht für Serotonin-Boost.', 1, 'low', 'short', 'evidence_based', 'somatic', '2026-03-18 00:00:22'),
(24, 4, 'Sonnenlicht-Exposition', '15 Minuten Sonnenlicht für Serotonin-Boost.', 1, 'low', 'short', 'evidence_based', 'somatic', '2026-03-18 00:00:23'),
(25, 4, 'Power Pose', '2 Minuten in kraftvoller Pose stehen.', 1, 'low', 'micro', 'anecdotal', 'somatic', '2026-03-18 00:00:24'),
(26, 4, 'Power Pose', '2 Minuten in kraftvoller Pose stehen.', 1, 'low', 'micro', 'anecdotal', 'somatic', '2026-03-18 00:00:25'),

-- Survival Skills (Category 5)
(27, 5, 'Minimum Viable Day', 'Was ist das absolute Minimum heute?', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-18 00:00:26'),
(28, 5, 'Minimaler Tag', 'Was ist das absolute Minimum heute?', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-18 00:00:27'),
(29, 5, 'Shower Only', 'Nur duschen heute. Mehr nicht erwarten.', 1, 'low', 'short', 'anecdotal', 'somatic', '2026-03-18 00:00:28'),
(30, 5, 'Nur Duschen', 'Nur duschen heute. Mehr nicht erwarten.', 1, 'low', 'short', 'anecdotal', 'somatic', '2026-03-18 00:00:29'),

-- Social Skills (Category 6)
(31, 6, 'Gratitude Text', 'Schicke jemandem eine Dankesnachricht.', 1, 'low', 'micro', 'anecdotal', 'mindfulness', '2026-03-18 00:00:30'),
(32, 6, 'Dankes-Text', 'Schicke jemandem eine Dankesnachricht.', 1, 'low', 'micro', 'anecdotal', 'mindfulness', '2026-03-18 00:00:31'),
(33, 6, 'Active Listening', '5 Minuten aktiv zuhören ohne zu unterbrechen.', 2, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-18 00:00:32'),
(34, 6, 'Aktives Zuhören', '5 Minuten aktiv zuhören ohne zu unterbrechen.', 2, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-18 00:00:33'),

-- Mindset Skills (Category 7)
(35, 7, 'Cognitive Reframing', 'Finde 3 alternative Erklärungen für die Situation.', 2, 'medium', 'short', 'evidence_based', 'CBT', '2026-03-18 00:00:34'),
(36, 7, 'Kognitive Umdeutung', 'Finde 3 alternative Erklärungen für die Situation.', 2, 'medium', 'short', 'evidence_based', 'CBT', '2026-03-18 00:00:35'),
(37, 7, 'Self-Compassion Break', 'Was würde ich einem Freund sagen?', 2, 'low', 'micro', 'evidence_based', 'mindfulness', '2026-03-18 00:00:36'),
(38, 7, 'Selbstmitgefühls-Pause', 'Was würde ich einem Freund sagen?', 2, 'low', 'micro', 'evidence_based', 'mindfulness', '2026-03-18 00:00:37'),

-- ÖH Skills (Category 8)
(39, 8, 'Meeting Prep', 'Bereite Agenda und Ziele für ÖH-Sitzung vor.', 2, 'medium', 'short', 'anecdotal', 'custom', '2026-03-18 00:00:38'),
(40, 8, 'Sitzungsvorbereitung', 'Bereite Agenda und Ziele für ÖH-Sitzung vor.', 2, 'medium', 'short', 'anecdotal', 'custom', '2026-03-18 00:00:39'),
(41, 8, 'Policy Research', 'Recherchiere Position zu aktuellem Thema.', 3, 'high', 'deep', 'anecdotal', 'custom', '2026-03-18 00:00:40'),
(42, 8, 'Politik-Recherche', 'Recherchiere Position zu aktuellem Thema.', 3, 'high', 'deep', 'anecdotal', 'custom', '2026-03-18 00:00:41'),

-- School Skills (Category 9)
(43, 9, 'Active Recall', 'Teste dich selbst ohne Buch.', 2, 'medium', 'short', 'evidence_based', 'CBT', '2026-03-18 00:00:42'),
(44, 9, 'Aktives Abrufen', 'Teste dich selbst ohne Buch.', 2, 'medium', 'short', 'evidence_based', 'CBT', '2026-03-18 00:00:43'),
(45, 9, 'Pomodoro Study', '25 Minuten lernen, 5 Minuten Pause.', 2, 'medium', 'short', 'evidence_based', 'habit_theory', '2026-03-18 00:00:44'),
(46, 9, 'Pomodoro Lernen', '25 Minuten lernen, 5 Minuten Pause.', 2, 'medium', 'short', 'evidence_based', 'habit_theory', '2026-03-18 00:00:45'),

-- Life Skills (Category 10)
(47, 10, 'Meal Prep', 'Bereite Mahlzeiten für die Woche vor.', 2, 'medium', 'deep', 'anecdotal', 'custom', '2026-03-18 00:00:46'),
(48, 10, 'Mahlzeiten-Vorbereitung', 'Bereite Mahlzeiten für die Woche vor.', 2, 'medium', 'deep', 'anecdotal', 'custom', '2026-03-18 00:00:47'),
(49, 10, 'Laundry Day', 'Waschmaschine anstellen und falten.', 2, 'medium', 'deep', 'anecdotal', 'custom', '2026-03-18 00:00:48'),
(50, 10, 'Waschtag', 'Waschmaschine anstellen und falten.', 2, 'medium', 'deep', 'anecdotal', 'custom', '2026-03-18 00:00:49'),

-- Work Skills (Category 11)
(51, 11, 'Email Zero', 'Leere Inbox komplett.', 2, 'high', 'deep', 'anecdotal', 'custom', '2026-03-18 00:00:50'),
(52, 11, 'Email Zero', 'Leere Inbox komplett.', 2, 'high', 'deep', 'anecdotal', 'custom', '2026-03-18 00:00:51'),
(53, 11, 'Deep Work Block', '2 Stunden ununterbrochene Konzentration.', 3, 'high', 'deep', 'evidence_based', 'custom', '2026-03-18 00:00:52'),
(54, 11, 'Deep Work Block', '2 Stunden ununterbrochene Konzentration.', 3, 'high', 'deep', 'evidence_based', 'custom', '2026-03-18 00:00:53');

SET FOREIGN_KEY_CHECKS = 1;
