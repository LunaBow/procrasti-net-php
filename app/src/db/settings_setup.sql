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

