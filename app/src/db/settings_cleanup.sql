-- This migration fixes the user_settings table by:
-- 1. Creating a temporary table with the latest settings per user
-- 2. Dropping the old table
-- 3. Renaming the temp table back to user_settings with proper constraints

-- Create temporary table with only the latest settings per user
CREATE TABLE `user_settings_new` (
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

-- Copy only the latest settings for each user
INSERT INTO `user_settings_new`
SELECT * FROM (
  SELECT user_id, allow_gamification, privacy_mode, sarcastic_comments, hand_drawn_mode, leet_speak, MAX(updated_at) as updated_at
  FROM `user_settings`
  GROUP BY user_id
) AS latest;

-- Drop old table and rename new one
DROP TABLE `user_settings`;
RENAME TABLE `user_settings_new` TO `user_settings`;

