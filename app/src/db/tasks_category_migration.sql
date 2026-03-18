-- Add category support to tasks table
ALTER TABLE `tasks` ADD COLUMN `category_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `user_id`;
ALTER TABLE `tasks` ADD CONSTRAINT `fk_tasks_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

-- Update existing tasks to have a default category (Planning)
UPDATE `tasks` SET `category_id` = 2 WHERE `category_id` IS NULL;
