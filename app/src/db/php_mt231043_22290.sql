-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 18, 2026 at 01:17 PM
-- Server version: 10.11.14-MariaDB-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_mt231043_22290`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `color_code` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `color_code`) VALUES
(1, 'Focus', 'Aufmerksamkeit, Starten, Ablenkungen reduzieren', '#4a5d50'),
(2, 'Planning', 'Planung, Prioritäten, Struktur', '#d68f7a'),
(3, 'Regulation', 'Emotionsregulation, Nervensystem, Stress', '#2d3e33'),
(4, 'Energy', 'Aktivierung, Antrieb, Körper in Gang bringen', '#e6d5c3'),
(5, 'Survival', 'Low energy, Minimum viable day, Notfallmodus', '#6b7a71'),
(6, 'Social', 'Kommunikation, Grenzen, Hilfe holen', '#8fa395'),
(7, 'Mindset', 'Kognition, Reframing, Selbstmitgefühl', '#c47d68');

-- --------------------------------------------------------

--
-- Table structure for table `checkins`
--

CREATE TABLE `checkins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `mood` tinyint(3) UNSIGNED NOT NULL,
  `energy` tinyint(3) UNSIGNED NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checkins`
--

INSERT INTO `checkins` (`id`, `user_id`, `date`, `mood`, `energy`, `note`, `created_at`) VALUES
(1, 2, '2026-02-21', 3, 4, 'Wenig geschlafen. Kopf laut.', '2026-02-21 00:48:17'),
(2, 2, '2026-02-22', 4, 4, 'Okay-ish. Kleine Fortschritte.', '2026-02-22 00:48:17'),
(3, 2, '2026-02-23', 2, 3, 'Down. Alles fühlt sich schwer an.', '2026-02-23 00:48:17'),
(4, 2, '2026-02-24', 3, 5, 'Unruhig, aber produktiv.', '2026-02-24 00:48:17'),
(5, 2, '2026-02-25', 5, 6, 'Kurz Flow erwischt.', '2026-02-25 00:48:17'),
(6, 2, '2026-02-26', 2, 2, 'Shutdown. Nur Minimum geschafft.', '2026-02-26 00:48:17'),
(7, 2, '2026-02-27', 3, 3, 'Besser. Kleine Struktur hilft.', '2026-02-27 00:48:17'),
(8, 2, '2026-02-28', 4, 4, 'Sozialer Kontakt hat gut getan.', '2026-02-28 00:48:17'),
(9, 2, '2026-03-01', 3, 5, 'ADHS-Tag. Timer saved me.', '2026-03-01 00:48:17'),
(10, 2, '2026-03-02', 4, 4, 'Noch müde. Aber ich bin hier.', '2026-03-02 00:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `contraindication_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `skill_id`, `name`, `step_by_step`, `duration_minutes`, `requires_material`, `indoor_outdoor`, `intensity`, `crisis_safe`, `is_guided`, `min_age`, `max_age`, `suitability_notes`, `contraindication_notes`) VALUES
(1, 39, 'TIPP: Cold Face Reset', '[\"Hol dir kaltes Wasser oder Kühlpack\", \"30–60 Sekunden Wangen/Schläfen kühlen\", \"Lang ausatmen, 3 Runden\", \"Danach: 1 Satz “Was ist der nächste sichere Schritt?”\"]', 3, 0, 'either', 'calm', 1, 0, 14, NULL, 'Gut bei akuter Anspannung/Impulsdruck.', 'Nicht bei bestimmten Herzproblemen oder wenn Kälte kontraindiziert ist.'),
(2, 87, '2-Minute “Stupid Easy” Start', '[\"Öffne nur die Datei/den Ordner\", \"Schreib eine Überschrift\", \"Setz einen 2-Minuten-Timer\", \"Wenn Timer endet: entscheide bewusst “noch 2” oder stopp\"]', 2, 0, 'indoor', 'neutral', 1, 0, 12, NULL, 'Perfekt bei ADHS/Startblockade.', NULL),
(3, 92, 'Behavioral Activation Micro-Date', '[\"Wähle 1 Mini-Aktivität (duschen, 5 min walk, Musik)\", \"Mach sie für 5–10 Minuten\", \"Notiere 1 Wort: “Energie vorher/nachher”\"]', 10, 0, 'either', 'neutral', 1, 0, 14, NULL, 'Gut bei Depression/Antrieb niedrig.', NULL),
(4, 62, 'Opposite Action: Mini-Step', '[\"Welche Emotion? (z.B. Angst/Wut/Scham)\", \"Passt sie zu den Fakten? Wenn nein: Gegenteil klein starten\", \"Wähle 1 Mini-Verhalten (z.B. freundlich antworten statt blocken)\", \"Nach 5 Minuten checken: Spannung/Impulse\"]', 10, 0, 'either', 'neutral', 1, 0, 14, NULL, 'DBT-Klassiker bei BPD-Emotionsspikes.', NULL),
(5, 80, 'DEAR MAN Script (90 Sekunden)', '[\"Describe: neutral beschreiben\", \"Express: Gefühl in 1 Satz\", \"Assert: Bitte/Nein klar\", \"Reinforce: warum es Sinn macht\", \"Mindful: broken record\", \"Appear confident\", \"Negotiate: 1 Alternative\"]', 15, 0, 'either', 'neutral', 1, 0, 14, NULL, 'Hilft bei Grenzen, Requests, RSD/Conflict.', NULL),
(6, 71, 'Radical Acceptance: 3 Sätze', '[\"Satz 1: “Die Realität ist gerade: ____.”\", \"Satz 2: “Ich mag es nicht, aber es ist so.”\", \"Satz 3: “Mein nächster hilfreicher Schritt ist: ____.”\"]', 6, 0, 'either', 'calm', 1, 0, 14, NULL, 'Gut gegen Festbeißen, “es darf nicht so sein”.', NULL),
(7, 61, 'Check the Facts: Quick Form', '[\"Was ist passiert (nur Fakten)?\", \"Welche Annahme mache ich?\", \"Welche alternative Erklärung gibt es?\", \"Welche Aktion wäre “effective”?\"]', 12, 0, 'either', 'neutral', 1, 0, 14, NULL, 'Hilft bei RSD, Triggern, Grübeln.', NULL),
(8, 92, 'Activation: 10-Min Clean Corner', '[\"Stelle Timer 10 Minuten\", \"Nur 1 Ecke/1 Oberfläche\", \"Stop wenn Timer klingelt\", \"Kurz notieren: Energie vorher/nachher\"]', 10, 0, 'indoor', 'neutral', 1, 0, 14, NULL, 'Für Depressions-Schwere: klein und begrenzt.', NULL),
(9, 89, 'Two-Timer Sprint (15/3)', '[\"Timer 15 Minuten Fokus\", \"Timer 3 Minuten Pause\", \"In Pause: stehen, Wasser, keine Apps\", \"Wiederholen: max 3 Runden\"]', 18, 0, 'indoor', 'neutral', 1, 0, 12, NULL, 'ADHS: externes Timing gegen Time Blindness.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exercise_runs`
--

CREATE TABLE `exercise_runs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `started_at` datetime DEFAULT current_timestamp(),
  `finished_at` datetime DEFAULT NULL,
  `tension_before` tinyint(3) UNSIGNED DEFAULT NULL,
  `tension_after` tinyint(3) UNSIGNED DEFAULT NULL,
  `energy_before` tinyint(3) UNSIGNED DEFAULT NULL,
  `energy_after` tinyint(3) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercise_runs`
--

INSERT INTO `exercise_runs` (`id`, `user_id`, `exercise_id`, `started_at`, `finished_at`, `tension_before`, `tension_after`, `energy_before`, `energy_after`, `note`) VALUES
(1, 2, 1, '2026-02-21 10:56:52', '2026-02-21 11:01:52', 8, 5, 3, 4, 'Anfang war innerer Alarm. Danach ok.'),
(2, 2, 2, '2026-02-22 14:56:52', '2026-02-22 14:58:52', 6, 5, 2, 3, 'Nur gestartet. Hat gereicht um nicht zu spiralen.'),
(3, 2, 3, '2026-02-23 12:56:52', '2026-02-23 13:06:52', 5, 4, 2, 4, 'Kleiner Push, danach weitergemacht.'),
(4, 2, 7, '2026-02-24 09:56:52', '2026-02-24 10:08:52', 7, 6, 3, 4, 'RSD war hoch, Fakten-Check hat geholfen.'),
(5, 2, 5, '2026-02-25 18:56:52', '2026-02-25 19:11:52', 6, 4, 4, 4, 'DEAR MAN vorbereitet statt impulsiv zu texten.'),
(6, 2, 1, '2026-02-28 09:56:52', '2026-02-28 10:00:52', 6, 4, 3, 4, 'Run auto-seeded.'),
(7, 2, 7, '2026-02-22 15:56:52', '2026-02-22 16:06:52', 8, 6, 4, 5, 'Run auto-seeded.'),
(8, 2, 6, '2026-02-23 14:56:52', '2026-02-23 15:05:52', 7, 5, 3, 4, 'Run auto-seeded.'),
(9, 2, 5, '2026-02-24 13:56:52', '2026-02-24 14:04:52', 6, 4, 2, 3, 'Run auto-seeded.'),
(10, 2, 2, '2026-02-27 10:56:52', '2026-02-27 11:01:52', 7, 5, 4, 5, 'Run auto-seeded.'),
(11, 2, 9, '2026-03-01 17:56:52', '2026-03-01 18:08:52', 6, 4, 6, 7, 'Run auto-seeded.'),
(12, 2, 8, '2026-02-21 16:56:52', '2026-02-21 17:07:52', 5, 3, 5, 6, 'Run auto-seeded.'),
(13, 2, 3, '2026-02-26 11:56:52', '2026-02-26 12:02:52', 8, 6, 5, 6, 'Run auto-seeded.');

-- --------------------------------------------------------

--
-- Table structure for table `exercise_tags`
--

CREATE TABLE `exercise_tags` (
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `habits`
--

CREATE TABLE `habits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `habits`
--

INSERT INTO `habits` (`id`, `user_id`, `name`, `created_at`) VALUES
(1, 2, 'Learn for exam', '2026-03-16 12:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `habit_logs`
--

CREATE TABLE `habit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `habit_id` bigint(20) UNSIGNED NOT NULL,
  `done_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `habit_logs`
--

INSERT INTO `habit_logs` (`id`, `habit_id`, `done_date`) VALUES
(1, 1, '2026-03-16'),
(3, 1, '2026-03-17');

-- --------------------------------------------------------

--
-- Table structure for table `routines`
--

CREATE TABLE `routines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `recurrence_type` enum('daily','weekly','custom') DEFAULT 'weekly',
  `recurrence_rule` varchar(120) DEFAULT NULL,
  `target_per_week` tinyint(3) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `weekdays` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`weekdays`)),
  `reminder_time` time DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `routines`
--

INSERT INTO `routines` (`id`, `user_id`, `title`, `description`, `recurrence_type`, `recurrence_rule`, `target_per_week`, `is_active`, `created_at`, `weekdays`, `reminder_time`, `updated_at`) VALUES
(1, 2, 'Daily Reset', 'Wasser + 2 Minuten Movement + 1 Fokus-Timer', 'daily', NULL, 7, 1, '2026-02-23 00:48:17', '[1, 2, 3, 4, 5, 6, 7]', '10:30:00', '2026-03-03 01:52:28'),
(1, 2, 'Daily Reset', 'Wasser + 2 Minuten Movement + 1 Fokus-Timer', 'daily', NULL, 7, 1, '2026-02-23 00:48:17', '[1, 2, 3, 4, 5, 6, 7]', '10:30:00', '2026-03-03 01:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `routine_logs`
--

CREATE TABLE `routine_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `routine_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `done_at` datetime DEFAULT current_timestamp(),
  `mood_after` tinyint(3) UNSIGNED DEFAULT NULL,
  `energy_after` tinyint(3) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `done_date` date GENERATED ALWAYS AS (cast(`done_at` as date)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `routine_logs`
--

INSERT INTO `routine_logs` (`id`, `routine_id`, `user_id`, `done_at`, `mood_after`, `energy_after`, `note`) VALUES
(1, 1, 2, '2026-02-25 00:48:17', 4, 5, 'Hat geholfen, Kopf weniger laut.'),
(2, 1, 2, '2026-02-28 00:48:17', 3, 4, 'Nicht perfekt, aber gemacht.'),
(1, 1, 2, '2026-02-25 00:48:17', 4, 5, 'Hat geholfen, Kopf weniger laut.'),
(2, 1, 2, '2026-02-28 00:48:17', 3, 4, 'Nicht perfekt, aber gemacht.');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(160) NOT NULL,
  `description` text DEFAULT NULL,
  `difficulty_level` tinyint(3) UNSIGNED DEFAULT 1,
  `energy_required` enum('low','medium','high') DEFAULT 'low',
  `time_investment_type` enum('micro','short','deep') DEFAULT 'micro',
  `evidence_level` enum('none','anecdotal','clinical_practice','evidence_based') DEFAULT 'none',
  `source_type` enum('CBT','DBT','habit_theory','mindfulness','somatic','custom','other') DEFAULT 'custom',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `category_id`, `name`, `description`, `difficulty_level`, `energy_required`, `time_investment_type`, `evidence_level`, `source_type`, `created_at`) VALUES
(1, 1, '5-Minute Rule', 'Starte nur 5 Minuten. Danach darfst du aufhören oder weitermachen.', 1, 'low', 'micro', 'anecdotal', 'habit_theory', '2026-03-02 00:43:09'),
(2, 1, 'Pomodoro 25/5', '25 Minuten Fokus, 5 Minuten Pause. Wiederholen.', 1, 'medium', 'short', 'evidence_based', 'habit_theory', '2026-03-02 00:43:09'),
(3, 1, 'Distraction Dump', 'Alle Ablenkungen kurz notieren statt ihnen zu folgen.', 1, 'low', 'micro', 'anecdotal', 'mindfulness', '2026-03-02 00:43:09'),
(4, 1, 'Single-Tab Mode', 'Nur ein Tab/ein Fenster. Alles andere zu.', 2, 'low', 'short', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(5, 1, 'Start Ritual', 'Mini-Ritual als Startsignal (Song, Tee, Timer).', 1, 'low', 'micro', 'anecdotal', 'habit_theory', '2026-03-02 00:43:09'),
(6, 1, '2-Minute Setup', 'Arbeitsplatz 2 Minuten vorbereiten, dann erst anfangen.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(7, 1, 'Body Double', 'Mit jemandem parallel arbeiten (Call, Raum, Co-working).', 2, 'medium', 'deep', 'clinical_practice', 'other', '2026-03-02 00:43:09'),
(8, 2, 'Top 3 Priorities', 'Nur 3 Aufgaben zählen heute. Alles andere Bonus.', 1, 'low', 'micro', 'evidence_based', 'habit_theory', '2026-03-02 00:43:09'),
(9, 2, 'Next Physical Action', 'Definiere den nächsten konkreten physischen Schritt.', 1, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(10, 2, 'Time Boxing', 'Gib Aufgaben fixe Zeitfenster statt “bis fertig”.', 2, 'medium', 'short', 'evidence_based', 'habit_theory', '2026-03-02 00:43:09'),
(11, 2, 'Reverse Planning', 'Vom Ziel rückwärts in Schritte planen.', 2, 'medium', 'short', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(12, 2, 'If-Then Plan', 'Wenn X passiert, dann mache ich Y (Implementation Intention).', 2, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(13, 2, 'Break into Chunks', 'Großes Ziel in kleine, prüfbare Teilaufgaben.', 1, 'low', 'short', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(14, 2, 'Done List', 'Liste, was du geschafft hast. Gegen Gehirn-Gaslighting.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(15, 3, 'Box Breathing', '4 ein, 4 halten, 4 aus, 4 halten. 4 Runden.', 1, 'low', 'micro', 'evidence_based', 'mindfulness', '2026-03-02 00:43:09'),
(16, 3, 'Name the Emotion', 'Gefühl benennen (“Anxiety”, “Shame”) statt verschwimmen.', 1, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:43:09'),
(17, 3, 'STOP Skill', 'Stop, Take a step back, Observe, Proceed mindfully.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:43:09'),
(18, 3, 'Grounding 5-4-3-2-1', '5 sehen, 4 fühlen, 3 hören, 2 riechen, 1 schmecken.', 1, 'low', 'micro', 'clinical_practice', 'mindfulness', '2026-03-02 00:43:09'),
(19, 3, 'Progressive Muscle Relaxation', 'Anspannen/entspannen in Muskelgruppen.', 2, 'low', 'short', 'evidence_based', 'somatic', '2026-03-02 00:43:09'),
(20, 3, 'Self-Validation', '“Das ergibt Sinn, dass ich so fühle.” Ohne Drama, ohne Lüge.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:43:09'),
(21, 4, '2-Minute Movement hi', 'Kurz bewegen: Treppe, Squats, Jumping Jacks.', 1, 'medium', 'micro', 'anecdotal', 'somatic', '2026-03-02 00:43:09'),
(22, 4, 'Sunlight 10', '10 Minuten Tageslicht für Wachheit und Rhythmus.', 1, 'low', 'short', 'evidence_based', 'other', '2026-03-02 00:43:09'),
(23, 4, 'Hydration Check', 'Ein Glas Wasser. Ja, wirklich.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(24, 4, 'Snack for Blood Sugar', 'Kleine Mahlzeit gegen “ich kann nicht denken”.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(25, 4, 'Activation Playlist', '1 Song als Startknopf. Nicht diskutieren.', 1, 'medium', 'micro', 'anecdotal', 'habit_theory', '2026-03-02 00:43:09'),
(26, 4, 'Walk-and-Think', '5–15 Minuten gehen, dann zurück an die Aufgabe.', 2, 'medium', 'short', 'anecdotal', 'somatic', '2026-03-02 00:43:09'),
(27, 5, 'Minimum Viable Day', 'Nur das Nötigste. Alles andere ist Luxus.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(28, 5, 'One Tiny Task', 'Nur 1 Mini-Task: Mail öffnen, Datei benennen, Login.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(29, 5, 'Shower Reset', 'Kurze Dusche als Nervensystem-Reset.', 1, 'low', 'short', 'anecdotal', 'somatic', '2026-03-02 00:43:09'),
(30, 5, 'Reduce Scope', 'Aufgabe absichtlich kleiner machen, statt aufgeben.', 2, 'low', 'micro', 'clinical_practice', 'CBT', '2026-03-02 00:43:09'),
(31, 5, 'Ask for Extension Template', 'Standardtext: kurz, klar, ohne Overexplaining.', 2, 'low', 'micro', '', 'custom', '2026-03-02 00:43:09'),
(32, 6, 'Text a Human', 'Eine Person kurz pingen: “Kannst du 10 Min da sein?”', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:43:09'),
(33, 6, 'Boundary Script', '1 Satz Grenze + 1 Satz Alternative.', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(34, 6, 'Body Double Invite', '“Willst du 30 Min co-worken? Kamera off ok.”', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(35, 7, 'Reframe the Task', 'Von “muss perfekt” zu “nur Version 1”.', 2, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(36, 7, 'Self-Compassion Line', 'Rede mit dir wie mit einem Freund. (Leider effektiv.)', 2, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(37, 7, 'Cognitive Defusion', 'Gedanken sind Gedanken, nicht Befehle.', 3, 'low', 'short', 'clinical_practice', 'mindfulness', '2026-03-02 00:43:09'),
(38, 7, 'Evidence Check', 'Welche Fakten sprechen dafür/dagegen, dass es “katastrophal” ist?', 3, 'medium', 'short', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(39, 3, 'TIPP (Temperature)', 'Kaltwasser/Cold pack 30–60s, um körperlich runterzuschalten.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(40, 3, 'TIPP (Intense Exercise)', '20–60s kurze intensive Bewegung, um Stressenergie zu entladen.', 2, 'medium', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(41, 3, 'TIPP (Paced Breathing)', 'Langsames Atmen (z.B. 4 ein, 6 aus) 2–3 Minuten.', 1, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(42, 3, 'TIPP (Paired Muscle Relaxation)', 'Anspannen beim Einatmen, lösen beim Ausatmen.', 2, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(43, 3, 'Pros & Cons (Urge)', 'Pro/Contra von “Impuls nachgeben” vs “Skill nutzen”.', 3, 'low', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(44, 3, 'Urge Surfing', 'Impuls wie eine Welle beobachten bis er abflacht.', 3, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(45, 3, 'Half-Smile', 'Mini-Lächeln + lockere Hände, um den Körper zu beeinflussen.', 2, 'low', 'micro', 'anecdotal', 'DBT', '2026-03-02 00:44:58'),
(46, 3, 'Willing Hands', 'Hände öffnen/entspannen statt festklammern.', 2, 'low', 'micro', 'anecdotal', 'DBT', '2026-03-02 00:44:58'),
(47, 3, 'ACCEPTS (Activities)', 'Ablenkung durch Aktivität, kurz und bewusst.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(48, 3, 'ACCEPTS (Contributing)', 'Kleiner hilfreicher Schritt für jemand anderen.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(49, 3, 'ACCEPTS (Comparisons)', 'Vergleich mit früheren Krisen: “Ich hab das überlebt.”', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(50, 3, 'ACCEPTS (Emotions)', 'Gegenemotion triggern: Musik, Humor, Tierbilder.', 1, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(51, 3, 'ACCEPTS (Pushing away)', 'Thema kurz parken, mental “in eine Box”.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(52, 3, 'ACCEPTS (Thoughts)', 'Kopfrechnen, Puzzle, 5 Dinge aufzählen. Gehirn umlenken.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(53, 3, 'ACCEPTS (Sensations)', 'Sinnesreiz: Kaugummi, scharfes Bonbon, Duft.', 1, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(54, 3, 'IMPROVE (Imagery)', 'Sicherer Ort vorstellen, 60 Sekunden.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(55, 3, 'IMPROVE (Meaning)', '“Wofür lohnt sich das grade?” Mini-Sinn finden.', 3, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(56, 3, 'IMPROVE (Prayer)', 'Wenn du magst: inneres Bitten um Stärke/Ruhe.', 2, 'low', 'micro', 'anecdotal', 'DBT', '2026-03-02 00:44:58'),
(57, 3, 'IMPROVE (Relaxation)', 'Kurzentspannung: Schultern senken, Kiefer lösen.', 1, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(58, 3, 'IMPROVE (One thing)', 'Nur diese eine Minute. Nicht der Rest des Lebens.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(59, 3, 'IMPROVE (Vacation)', 'Mini-Auszeit: 3 Minuten nichts müssen.', 1, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(60, 3, 'IMPROVE (Encouragement)', 'Selbst-Zuspruch als Satz: “Ich schaff die nächsten 10 Min.”', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(61, 3, 'Check the Facts', 'Fakten checken: passt die Intensität zur Situation?', 3, 'low', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(62, 3, 'Opposite Action', 'Wenn Emotion nicht “faktengerecht”: Gegenteil tun.', 4, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(63, 3, 'PLEASE (Sleep)', 'Schlaf priorisieren, weil Emotionen sonst brennen.', 2, 'low', 'deep', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(64, 3, 'PLEASE (Eat)', 'Regelmäßig essen: Blutzucker = Stabilität.', 1, 'low', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(65, 3, 'PLEASE (Substances)', 'Substanzkonsum reduzieren, weil er Skills sabotiert.', 4, 'low', 'deep', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(66, 3, 'PLEASE (Exercise)', 'Bewegung als Emotionspuffer, nicht als Strafe.', 2, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(67, 3, 'PLEASE (Health)', 'Körperliches abklären, bevor alles psychologisiert wird.', 3, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(68, 3, 'Build Positive Experiences (Short)', 'Jeden Tag 1 kleines positives Ding planen.', 2, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(69, 3, 'Build Positive Experiences (Long)', 'Langfristig Dinge aufbauen, die dir Bedeutung geben.', 3, 'medium', 'deep', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(70, 3, 'Cope Ahead', 'Schwierige Situation vorher durchspielen + Skills planen.', 4, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(71, 7, 'Radical Acceptance', 'Akzeptieren was ist, ohne es gut zu finden.', 4, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(72, 7, 'Dialectical Thinking', '2 Dinge können gleichzeitig wahr sein.', 3, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(73, 7, 'Wise Mind', 'Balance aus Gefühl und Vernunft ansteuern.', 3, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(74, 7, 'Mindfulness “What” (Observe)', 'Beobachten ohne zu bewerten.', 2, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(75, 7, 'Mindfulness “What” (Describe)', 'Beschreiben statt interpretieren.', 2, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(76, 7, 'Mindfulness “What” (Participate)', 'Mitmachen statt innerlich weg sein.', 3, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(77, 7, 'Mindfulness “How” (Non-judgmental)', 'Nicht werten. Benennen. Weiter.', 3, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(78, 7, 'Mindfulness “How” (One-mindfully)', 'Eine Sache auf einmal, bewusst.', 2, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(79, 7, 'Mindfulness “How” (Effectively)', 'Was funktioniert, nicht was “fair” ist.', 3, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(80, 6, 'DEAR MAN', 'Bitte/Nein sagen: Describe, Express, Assert, Reinforce…', 4, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(81, 6, 'GIVE', 'Beziehung pflegen: Gentle, Interested, Validate, Easy manner.', 3, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(82, 6, 'FAST', 'Selbstrespekt: Fair, no Apologies, Stick to values, Truthful.', 3, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(83, 6, 'Validation (Others)', 'Validieren ohne zuzustimmen: “Das klingt echt hart.”', 3, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(84, 6, 'Boundary “No + Because + Alternative”', 'Nein sagen + kurz warum + Alternative/Ende.', 3, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:44:58'),
(85, 6, 'Repair Attempt', 'Nach Konflikt: 1 Satz Verantwortung + 1 Satz Wunsch.', 4, 'medium', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(87, 1, 'Start with “Stupid Easy”', 'Mach den ersten Schritt absurd klein: Datei öffnen, Titel schreiben.', 1, 'low', 'micro', 'clinical_practice', 'habit_theory', '2026-03-02 00:48:17'),
(88, 2, 'Externalize Memory', 'Alles raus aus dem Kopf: Liste, Sticky, Notion. Kopf ist kein RAM.', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:48:17'),
(89, 2, 'Two-Timer System', 'Ein Timer für Fokus + ein Timer für Pausen. Keine Diskussion.', 2, 'low', 'short', 'anecdotal', 'habit_theory', '2026-03-02 00:48:17'),
(90, 1, 'No-Zero Rule', 'Jeden Tag 1 Mini-Schritt, auch wenn’s nur “öffnen” ist.', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:48:17'),
(91, 2, 'Task “Definition of Done”', '1 Satz: Was zählt als fertig?', 2, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:48:17'),
(92, 4, 'Behavioral Activation: Tiny', 'Eine kleine Aktivität mit minimaler Hürde planen und machen.', 3, 'medium', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:48:17'),
(93, 4, 'Pleasure vs Mastery', '1 Sache für Freude + 1 Sache für “ich kann was”.', 3, 'medium', 'short', 'evidence_based', 'CBT', '2026-03-02 00:48:17'),
(94, 5, 'Shame Detox Note', 'Ein Satz: “Ich bin nicht faul, ich kämpfe.”', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:48:17'),
(95, 5, 'Care Minimums', 'Essen, Wasser, Meds, Hygiene. Alles andere Bonus.', 1, 'low', 'micro', 'clinical_practice', 'custom', '2026-03-02 00:48:17'),
(96, 3, 'Interpersonal Trigger Map', 'Trigger → Gefühl → Impuls → Skill. Kurz notieren.', 4, 'medium', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:48:17'),
(97, 3, 'After-Conflict Cooldown', '20 Minuten keine Entscheidungen, kein Texten, nur runterregeln.', 4, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:48:17'),
(98, 7, 'Self-Invalidation Catch', '“Ich übertreibe” erkennen und ersetzen durch Fakten/Validierung.', 4, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:48:17'),
(1, 1, '5-Minute Rule', 'Starte nur 5 Minuten. Danach darfst du aufhören oder weitermachen.', 1, 'low', 'micro', 'anecdotal', 'habit_theory', '2026-03-02 00:43:09'),
(2, 1, 'Pomodoro 25/5', '25 Minuten Fokus, 5 Minuten Pause. Wiederholen.', 1, 'medium', 'short', 'evidence_based', 'habit_theory', '2026-03-02 00:43:09'),
(3, 1, 'Distraction Dump', 'Alle Ablenkungen kurz notieren statt ihnen zu folgen.', 1, 'low', 'micro', 'anecdotal', 'mindfulness', '2026-03-02 00:43:09'),
(4, 1, 'Single-Tab Mode', 'Nur ein Tab/ein Fenster. Alles andere zu.', 2, 'low', 'short', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(5, 1, 'Start Ritual', 'Mini-Ritual als Startsignal (Song, Tee, Timer).', 1, 'low', 'micro', 'anecdotal', 'habit_theory', '2026-03-02 00:43:09'),
(6, 1, '2-Minute Setup', 'Arbeitsplatz 2 Minuten vorbereiten, dann erst anfangen.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(7, 1, 'Body Double', 'Mit jemandem parallel arbeiten (Call, Raum, Co-working).', 2, 'medium', 'deep', 'clinical_practice', 'other', '2026-03-02 00:43:09'),
(8, 2, 'Top 3 Priorities', 'Nur 3 Aufgaben zählen heute. Alles andere Bonus.', 1, 'low', 'micro', 'evidence_based', 'habit_theory', '2026-03-02 00:43:09'),
(9, 2, 'Next Physical Action', 'Definiere den nächsten konkreten physischen Schritt.', 1, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(10, 2, 'Time Boxing', 'Gib Aufgaben fixe Zeitfenster statt “bis fertig”.', 2, 'medium', 'short', 'evidence_based', 'habit_theory', '2026-03-02 00:43:09'),
(11, 2, 'Reverse Planning', 'Vom Ziel rückwärts in Schritte planen.', 2, 'medium', 'short', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(12, 2, 'If-Then Plan', 'Wenn X passiert, dann mache ich Y (Implementation Intention).', 2, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(13, 2, 'Break into Chunks', 'Großes Ziel in kleine, prüfbare Teilaufgaben.', 1, 'low', 'short', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(14, 2, 'Done List', 'Liste, was du geschafft hast. Gegen Gehirn-Gaslighting.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(15, 3, 'Box Breathing', '4 ein, 4 halten, 4 aus, 4 halten. 4 Runden.', 1, 'low', 'micro', 'evidence_based', 'mindfulness', '2026-03-02 00:43:09'),
(16, 3, 'Name the Emotion', 'Gefühl benennen (“Anxiety”, “Shame”) statt verschwimmen.', 1, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:43:09'),
(17, 3, 'STOP Skill', 'Stop, Take a step back, Observe, Proceed mindfully.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:43:09'),
(18, 3, 'Grounding 5-4-3-2-1', '5 sehen, 4 fühlen, 3 hören, 2 riechen, 1 schmecken.', 1, 'low', 'micro', 'clinical_practice', 'mindfulness', '2026-03-02 00:43:09'),
(19, 3, 'Progressive Muscle Relaxation', 'Anspannen/entspannen in Muskelgruppen.', 2, 'low', 'short', 'evidence_based', 'somatic', '2026-03-02 00:43:09'),
(20, 3, 'Self-Validation', '“Das ergibt Sinn, dass ich so fühle.” Ohne Drama, ohne Lüge.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:43:09'),
(21, 4, '2-Minute Movement hi', 'Kurz bewegen: Treppe, Squats, Jumping Jacks.', 1, 'medium', 'micro', 'anecdotal', 'somatic', '2026-03-02 00:43:09'),
(22, 4, 'Sunlight 10', '10 Minuten Tageslicht für Wachheit und Rhythmus.', 1, 'low', 'short', 'evidence_based', 'other', '2026-03-02 00:43:09'),
(23, 4, 'Hydration Check', 'Ein Glas Wasser. Ja, wirklich.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(24, 4, 'Snack for Blood Sugar', 'Kleine Mahlzeit gegen “ich kann nicht denken”.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(25, 4, 'Activation Playlist', '1 Song als Startknopf. Nicht diskutieren.', 1, 'medium', 'micro', 'anecdotal', 'habit_theory', '2026-03-02 00:43:09'),
(26, 4, 'Walk-and-Think', '5–15 Minuten gehen, dann zurück an die Aufgabe.', 2, 'medium', 'short', 'anecdotal', 'somatic', '2026-03-02 00:43:09'),
(27, 5, 'Minimum Viable Day', 'Nur das Nötigste. Alles andere ist Luxus.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(28, 5, 'One Tiny Task', 'Nur 1 Mini-Task: Mail öffnen, Datei benennen, Login.', 1, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(29, 5, 'Shower Reset', 'Kurze Dusche als Nervensystem-Reset.', 1, 'low', 'short', 'anecdotal', 'somatic', '2026-03-02 00:43:09'),
(30, 5, 'Reduce Scope', 'Aufgabe absichtlich kleiner machen, statt aufgeben.', 2, 'low', 'micro', 'clinical_practice', 'CBT', '2026-03-02 00:43:09'),
(31, 5, 'Ask for Extension Template', 'Standardtext: kurz, klar, ohne Overexplaining.', 2, 'low', 'micro', '', 'custom', '2026-03-02 00:43:09'),
(32, 6, 'Text a Human', 'Eine Person kurz pingen: “Kannst du 10 Min da sein?”', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:43:09'),
(33, 6, 'Boundary Script', '1 Satz Grenze + 1 Satz Alternative.', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(34, 6, 'Body Double Invite', '“Willst du 30 Min co-worken? Kamera off ok.”', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:43:09'),
(35, 7, 'Reframe the Task', 'Von “muss perfekt” zu “nur Version 1”.', 2, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(36, 7, 'Self-Compassion Line', 'Rede mit dir wie mit einem Freund. (Leider effektiv.)', 2, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(37, 7, 'Cognitive Defusion', 'Gedanken sind Gedanken, nicht Befehle.', 3, 'low', 'short', 'clinical_practice', 'mindfulness', '2026-03-02 00:43:09'),
(38, 7, 'Evidence Check', 'Welche Fakten sprechen dafür/dagegen, dass es “katastrophal” ist?', 3, 'medium', 'short', 'evidence_based', 'CBT', '2026-03-02 00:43:09'),
(39, 3, 'TIPP (Temperature)', 'Kaltwasser/Cold pack 30–60s, um körperlich runterzuschalten.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(40, 3, 'TIPP (Intense Exercise)', '20–60s kurze intensive Bewegung, um Stressenergie zu entladen.', 2, 'medium', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(41, 3, 'TIPP (Paced Breathing)', 'Langsames Atmen (z.B. 4 ein, 6 aus) 2–3 Minuten.', 1, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(42, 3, 'TIPP (Paired Muscle Relaxation)', 'Anspannen beim Einatmen, lösen beim Ausatmen.', 2, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(43, 3, 'Pros & Cons (Urge)', 'Pro/Contra von “Impuls nachgeben” vs “Skill nutzen”.', 3, 'low', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(44, 3, 'Urge Surfing', 'Impuls wie eine Welle beobachten bis er abflacht.', 3, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(45, 3, 'Half-Smile', 'Mini-Lächeln + lockere Hände, um den Körper zu beeinflussen.', 2, 'low', 'micro', 'anecdotal', 'DBT', '2026-03-02 00:44:58'),
(46, 3, 'Willing Hands', 'Hände öffnen/entspannen statt festklammern.', 2, 'low', 'micro', 'anecdotal', 'DBT', '2026-03-02 00:44:58'),
(47, 3, 'ACCEPTS (Activities)', 'Ablenkung durch Aktivität, kurz und bewusst.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(48, 3, 'ACCEPTS (Contributing)', 'Kleiner hilfreicher Schritt für jemand anderen.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(49, 3, 'ACCEPTS (Comparisons)', 'Vergleich mit früheren Krisen: “Ich hab das überlebt.”', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(50, 3, 'ACCEPTS (Emotions)', 'Gegenemotion triggern: Musik, Humor, Tierbilder.', 1, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(51, 3, 'ACCEPTS (Pushing away)', 'Thema kurz parken, mental “in eine Box”.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(52, 3, 'ACCEPTS (Thoughts)', 'Kopfrechnen, Puzzle, 5 Dinge aufzählen. Gehirn umlenken.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(53, 3, 'ACCEPTS (Sensations)', 'Sinnesreiz: Kaugummi, scharfes Bonbon, Duft.', 1, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(54, 3, 'IMPROVE (Imagery)', 'Sicherer Ort vorstellen, 60 Sekunden.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(55, 3, 'IMPROVE (Meaning)', '“Wofür lohnt sich das grade?” Mini-Sinn finden.', 3, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(56, 3, 'IMPROVE (Prayer)', 'Wenn du magst: inneres Bitten um Stärke/Ruhe.', 2, 'low', 'micro', 'anecdotal', 'DBT', '2026-03-02 00:44:58'),
(57, 3, 'IMPROVE (Relaxation)', 'Kurzentspannung: Schultern senken, Kiefer lösen.', 1, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(58, 3, 'IMPROVE (One thing)', 'Nur diese eine Minute. Nicht der Rest des Lebens.', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(59, 3, 'IMPROVE (Vacation)', 'Mini-Auszeit: 3 Minuten nichts müssen.', 1, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(60, 3, 'IMPROVE (Encouragement)', 'Selbst-Zuspruch als Satz: “Ich schaff die nächsten 10 Min.”', 2, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(61, 3, 'Check the Facts', 'Fakten checken: passt die Intensität zur Situation?', 3, 'low', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(62, 3, 'Opposite Action', 'Wenn Emotion nicht “faktengerecht”: Gegenteil tun.', 4, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(63, 3, 'PLEASE (Sleep)', 'Schlaf priorisieren, weil Emotionen sonst brennen.', 2, 'low', 'deep', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(64, 3, 'PLEASE (Eat)', 'Regelmäßig essen: Blutzucker = Stabilität.', 1, 'low', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(65, 3, 'PLEASE (Substances)', 'Substanzkonsum reduzieren, weil er Skills sabotiert.', 4, 'low', 'deep', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(66, 3, 'PLEASE (Exercise)', 'Bewegung als Emotionspuffer, nicht als Strafe.', 2, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(67, 3, 'PLEASE (Health)', 'Körperliches abklären, bevor alles psychologisiert wird.', 3, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(68, 3, 'Build Positive Experiences (Short)', 'Jeden Tag 1 kleines positives Ding planen.', 2, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(69, 3, 'Build Positive Experiences (Long)', 'Langfristig Dinge aufbauen, die dir Bedeutung geben.', 3, 'medium', 'deep', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(70, 3, 'Cope Ahead', 'Schwierige Situation vorher durchspielen + Skills planen.', 4, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(71, 7, 'Radical Acceptance', 'Akzeptieren was ist, ohne es gut zu finden.', 4, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(72, 7, 'Dialectical Thinking', '2 Dinge können gleichzeitig wahr sein.', 3, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(73, 7, 'Wise Mind', 'Balance aus Gefühl und Vernunft ansteuern.', 3, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(74, 7, 'Mindfulness “What” (Observe)', 'Beobachten ohne zu bewerten.', 2, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(75, 7, 'Mindfulness “What” (Describe)', 'Beschreiben statt interpretieren.', 2, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(76, 7, 'Mindfulness “What” (Participate)', 'Mitmachen statt innerlich weg sein.', 3, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(77, 7, 'Mindfulness “How” (Non-judgmental)', 'Nicht werten. Benennen. Weiter.', 3, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(78, 7, 'Mindfulness “How” (One-mindfully)', 'Eine Sache auf einmal, bewusst.', 2, 'low', 'micro', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(79, 7, 'Mindfulness “How” (Effectively)', 'Was funktioniert, nicht was “fair” ist.', 3, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(80, 6, 'DEAR MAN', 'Bitte/Nein sagen: Describe, Express, Assert, Reinforce…', 4, 'medium', 'short', 'evidence_based', 'DBT', '2026-03-02 00:44:58'),
(81, 6, 'GIVE', 'Beziehung pflegen: Gentle, Interested, Validate, Easy manner.', 3, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(82, 6, 'FAST', 'Selbstrespekt: Fair, no Apologies, Stick to values, Truthful.', 3, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(83, 6, 'Validation (Others)', 'Validieren ohne zuzustimmen: “Das klingt echt hart.”', 3, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(84, 6, 'Boundary “No + Because + Alternative”', 'Nein sagen + kurz warum + Alternative/Ende.', 3, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:44:58'),
(85, 6, 'Repair Attempt', 'Nach Konflikt: 1 Satz Verantwortung + 1 Satz Wunsch.', 4, 'medium', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:44:58'),
(87, 1, 'Start with “Stupid Easy”', 'Mach den ersten Schritt absurd klein: Datei öffnen, Titel schreiben.', 1, 'low', 'micro', 'clinical_practice', 'habit_theory', '2026-03-02 00:48:17'),
(88, 2, 'Externalize Memory', 'Alles raus aus dem Kopf: Liste, Sticky, Notion. Kopf ist kein RAM.', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:48:17'),
(89, 2, 'Two-Timer System', 'Ein Timer für Fokus + ein Timer für Pausen. Keine Diskussion.', 2, 'low', 'short', 'anecdotal', 'habit_theory', '2026-03-02 00:48:17'),
(90, 1, 'No-Zero Rule', 'Jeden Tag 1 Mini-Schritt, auch wenn’s nur “öffnen” ist.', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:48:17'),
(91, 2, 'Task “Definition of Done”', '1 Satz: Was zählt als fertig?', 2, 'low', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:48:17'),
(92, 4, 'Behavioral Activation: Tiny', 'Eine kleine Aktivität mit minimaler Hürde planen und machen.', 3, 'medium', 'micro', 'evidence_based', 'CBT', '2026-03-02 00:48:17'),
(93, 4, 'Pleasure vs Mastery', '1 Sache für Freude + 1 Sache für “ich kann was”.', 3, 'medium', 'short', 'evidence_based', 'CBT', '2026-03-02 00:48:17'),
(94, 5, 'Shame Detox Note', 'Ein Satz: “Ich bin nicht faul, ich kämpfe.”', 2, 'low', 'micro', 'anecdotal', 'custom', '2026-03-02 00:48:17'),
(95, 5, 'Care Minimums', 'Essen, Wasser, Meds, Hygiene. Alles andere Bonus.', 1, 'low', 'micro', 'clinical_practice', 'custom', '2026-03-02 00:48:17'),
(96, 3, 'Interpersonal Trigger Map', 'Trigger → Gefühl → Impuls → Skill. Kurz notieren.', 4, 'medium', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:48:17'),
(97, 3, 'After-Conflict Cooldown', '20 Minuten keine Entscheidungen, kein Texten, nur runterregeln.', 4, 'low', 'short', 'clinical_practice', 'DBT', '2026-03-02 00:48:17'),
(98, 7, 'Self-Invalidation Catch', '“Ich übertreibe” erkennen und ersetzen durch Fakten/Validierung.', 4, 'low', 'micro', 'clinical_practice', 'DBT', '2026-03-02 00:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `skill_tags`
--

CREATE TABLE `skill_tags` (
  `skill_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skill_tags`
--

INSERT INTO `skill_tags` (`skill_id`, `tag_id`) VALUES
(87, 2),
(87, 15),
(88, 2),
(88, 15),
(89, 2),
(89, 15),
(90, 2),
(90, 15),
(91, 2),
(91, 15),
(92, 3),
(92, 10),
(93, 3),
(93, 10),
(96, 1),
(96, 11),
(97, 1),
(97, 11),
(98, 1),
(98, 11),
(87, 2),
(87, 15),
(88, 2),
(88, 15),
(89, 2),
(89, 15),
(90, 2),
(90, 15),
(91, 2),
(91, 15),
(92, 3),
(92, 10),
(93, 3),
(93, 10),
(96, 1),
(96, 11),
(97, 1),
(97, 11),
(98, 1),
(98, 11);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `description`) VALUES
(1, 'Overwhelmed', 'Zu viel gleichzeitig, Startblockade, Stress hoch'),
(2, 'Shutdown', 'Erschöpfung, Leere, “nichts geht”'),
(3, 'Anxious', 'Anspannung, Grübeln, Nervosität'),
(4, 'Impulsive', 'Drang sofort zu handeln, Risiko/Spontanität'),
(5, 'Low Mood', 'Traurigkeit, Hoffnungslosigkeit, Antrieb niedrig'),
(6, 'Restless', 'Unruhe, ADHS-typisches “ich kann nicht sitzen”'),
(7, 'Rage', 'Wutspikes, Triggered, Reizüberflutung'),
(8, 'Numb', 'Taubheit, Dissoziation-ish, wenig Zugang zu Gefühl'),
(9, 'Rejection Sensitive', 'Krasse Reaktion auf Kritik/Abweisung (RSD)'),
(10, 'Focused', 'Flow/Arbeitsmodus'),
(1, 'Overwhelmed', 'Zu viel gleichzeitig, Startblockade, Stress hoch'),
(2, 'Shutdown', 'Erschöpfung, Leere, “nichts geht”'),
(3, 'Anxious', 'Anspannung, Grübeln, Nervosität'),
(4, 'Impulsive', 'Drang sofort zu handeln, Risiko/Spontanität'),
(5, 'Low Mood', 'Traurigkeit, Hoffnungslosigkeit, Antrieb niedrig'),
(6, 'Restless', 'Unruhe, ADHS-typisches “ich kann nicht sitzen”'),
(7, 'Rage', 'Wutspikes, Triggered, Reizüberflutung'),
(8, 'Numb', 'Taubheit, Dissoziation-ish, wenig Zugang zu Gefühl'),
(9, 'Rejection Sensitive', 'Krasse Reaktion auf Kritik/Abweisung (RSD)'),
(10, 'Focused', 'Flow/Arbeitsmodus');

-- --------------------------------------------------------

--
-- Table structure for table `state_exercise_map`
--

CREATE TABLE `state_exercise_map` (
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `priority` tinyint(3) UNSIGNED DEFAULT 3,
  `why_it_works` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `state_exercise_map`
--

INSERT INTO `state_exercise_map` (`state_id`, `exercise_id`, `priority`, `why_it_works`) VALUES
(1, 2, 1, 'Senkt Startbarriere, aktiviert Handlung statt Grübeln.'),
(2, 2, 1, 'Senkt Startbarriere, aktiviert Handlung statt Grübeln.'),
(2, 3, 2, 'Kleine Aktivierung kann Stimmung und Selbstwirksamkeit anheben.'),
(3, 1, 1, 'Runterreguliert das Nervensystem schnell, reduziert Impulsdruck.'),
(4, 1, 1, 'Runterreguliert das Nervensystem schnell, reduziert Impulsdruck.'),
(5, 3, 2, 'Kleine Aktivierung kann Stimmung und Selbstwirksamkeit anheben.'),
(6, 2, 1, 'Senkt Startbarriere, aktiviert Handlung statt Grübeln.'),
(7, 1, 1, 'Runterreguliert das Nervensystem schnell, reduziert Impulsdruck.'),
(8, 3, 2, 'Kleine Aktivierung kann Stimmung und Selbstwirksamkeit anheben.'),
(1, 2, 1, 'Senkt Startbarriere, aktiviert Handlung statt Grübeln.'),
(2, 2, 1, 'Senkt Startbarriere, aktiviert Handlung statt Grübeln.'),
(2, 3, 2, 'Kleine Aktivierung kann Stimmung und Selbstwirksamkeit anheben.'),
(3, 1, 1, 'Runterreguliert das Nervensystem schnell, reduziert Impulsdruck.'),
(4, 1, 1, 'Runterreguliert das Nervensystem schnell, reduziert Impulsdruck.'),
(5, 3, 2, 'Kleine Aktivierung kann Stimmung und Selbstwirksamkeit anheben.'),
(6, 2, 1, 'Senkt Startbarriere, aktiviert Handlung statt Grübeln.'),
(7, 1, 1, 'Runterreguliert das Nervensystem schnell, reduziert Impulsdruck.'),
(8, 3, 2, 'Kleine Aktivierung kann Stimmung und Selbstwirksamkeit anheben.');

-- --------------------------------------------------------

--
-- Table structure for table `state_logs`
--

CREATE TABLE `state_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `tension_level` tinyint(3) UNSIGNED NOT NULL,
  `energy_level` tinyint(3) UNSIGNED NOT NULL,
  `logged_at` datetime DEFAULT current_timestamp(),
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `state_logs`
--

INSERT INTO `state_logs` (`id`, `user_id`, `state_id`, `tension_level`, `energy_level`, `logged_at`, `note`) VALUES
(1, 2, 1, 7, 3, '2026-02-24 00:48:17', 'Zu viele Tabs im Kopf.'),
(2, 2, 7, 8, 4, '2026-02-25 00:48:17', 'Triggered, will irgendwen anschreien.'),
(3, 2, 6, 6, 6, '2026-02-26 00:48:17', 'Unruhig aber beweglich.'),
(4, 2, 5, 5, 2, '2026-02-27 00:48:17', 'Schwer, leer, langsam.'),
(5, 2, 10, 4, 5, '2026-02-28 00:48:17', 'Kurz Fokus gefunden.'),
(1, 2, 1, 7, 3, '2026-02-24 00:48:17', 'Zu viele Tabs im Kopf.'),
(2, 2, 7, 8, 4, '2026-02-25 00:48:17', 'Triggered, will irgendwen anschreien.'),
(3, 2, 6, 6, 6, '2026-02-26 00:48:17', 'Unruhig aber beweglich.'),
(4, 2, 5, 5, 2, '2026-02-27 00:48:17', 'Schwer, leer, langsam.'),
(5, 2, 10, 4, 5, '2026-02-28 00:48:17', 'Kurz Fokus gefunden.');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(2, 'ADHD'),
(4, 'Anxiety'),
(1, 'BPD'),
(10, 'CBT'),
(6, 'Crisis-safe'),
(11, 'DBT'),
(3, 'Depression'),
(15, 'Executive dysfunction'),
(7, 'Micro'),
(9, 'Mindfulness'),
(13, 'Planning'),
(14, 'Self-compassion'),
(5, 'Sleep'),
(12, 'Social'),
(8, 'Somatic'),
(2, 'ADHD'),
(4, 'Anxiety'),
(1, 'BPD'),
(10, 'CBT'),
(6, 'Crisis-safe'),
(11, 'DBT'),
(3, 'Depression'),
(15, 'Executive dysfunction'),
(7, 'Micro'),
(9, 'Mindfulness'),
(13, 'Planning'),
(14, 'Self-compassion'),
(5, 'Sleep'),
(12, 'Social'),
(8, 'Somatic');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('todo','doing','done','blocked','dropped') DEFAULT 'todo',
  `energy_required` enum('low','medium','high') DEFAULT 'medium',
  `estimated_minutes` smallint(5) UNSIGNED DEFAULT NULL,
  `due_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `energy_required`, `estimated_minutes`, `due_at`, `created_at`, `completed_at`, `updated_at`) VALUES
(1, 2, 'Projekt: Skills Seed einspielen', NULL, 'done', 'medium', 45, NULL, '2026-02-28 00:48:17', NULL, '2026-03-03 05:05:17'),
(2, 2, '2-Minute Start: Ordner aufräumen', NULL, 'done', 'medium', 10, NULL, '2026-03-01 00:48:17', NULL, '2026-03-03 05:05:17'),
(3, 2, 'Run: TIPP Cold Reset', NULL, 'done', 'medium', 5, NULL, '2026-03-01 00:48:17', NULL, '2026-03-03 05:05:18'),
(4, 2, 'Mini Walk', '10 Minuten raus, auch wenn’s nervt', 'done', 'medium', 10, '2026-02-27 00:48:17', '2026-02-27 00:48:17', '2026-02-27 00:48:17', '2026-03-03 01:52:28'),
(6, 2, 'aaaaaaaaaaaa', NULL, 'done', 'high', NULL, NULL, '2026-03-03 05:05:27', '2026-03-16 12:25:44', '2026-03-16 12:25:44'),
(7, 2, 'aaaaaaaaaaaa', NULL, 'todo', 'high', NULL, NULL, '2026-03-03 05:05:28', NULL, '2026-03-16 17:00:12'),
(1, 2, 'Projekt: Skills Seed einspielen', NULL, 'done', 'medium', 45, NULL, '2026-02-28 00:48:17', NULL, '2026-03-03 05:05:17'),
(2, 2, '2-Minute Start: Ordner aufräumen', NULL, 'done', 'medium', 10, NULL, '2026-03-01 00:48:17', NULL, '2026-03-03 05:05:17'),
(3, 2, 'Run: TIPP Cold Reset', NULL, 'done', 'medium', 5, NULL, '2026-03-01 00:48:17', NULL, '2026-03-03 05:05:18'),
(4, 2, 'Mini Walk', '10 Minuten raus, auch wenn’s nervt', 'done', 'medium', 10, '2026-02-27 00:48:17', '2026-02-27 00:48:17', '2026-02-27 00:48:17', '2026-03-03 01:52:28'),
(6, 2, 'aaaaaaaaaaaa', NULL, 'done', 'high', NULL, NULL, '2026-03-03 05:05:27', '2026-03-16 12:25:44', '2026-03-16 12:25:44'),
(7, 2, 'aaaaaaaaaaaa', NULL, 'todo', 'high', NULL, NULL, '2026-03-03 05:05:28', NULL, '2026-03-16 17:00:12');

-- --------------------------------------------------------

--
-- Table structure for table `task_chunks`
--

CREATE TABLE `task_chunks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `notes` text DEFAULT NULL,
  `estimated_minutes` smallint(5) UNSIGNED DEFAULT NULL,
  `status` enum('todo','doing','done','blocked','dropped') DEFAULT 'todo',
  `sort_order` tinyint(3) UNSIGNED DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_chunks`
--

INSERT INTO `task_chunks` (`id`, `task_id`, `title`, `notes`, `estimated_minutes`, `status`, `sort_order`, `created_at`, `completed_at`) VALUES
(1, 2, 'Open project + run dev server', 'Nur starten, keine Fixes.', 10, 'doing', 1, '2026-03-01 00:48:17', NULL),
(2, 2, 'Seed categories + skills', 'SQL laufen lassen, danach UI check.', 20, 'todo', 2, '2026-03-01 00:48:17', NULL),
(3, 2, 'Fix /todos mismatch', 'View oder Backend rename.', 15, 'todo', 3, '2026-03-01 00:48:17', NULL),
(4, 1, 'Chunk 2: tiny step', 'Auto-seeded chunk.', 10, 'todo', 1, '2026-02-28 00:48:17', NULL),
(5, 2, 'Chunk 3: tiny step', 'Auto-seeded chunk.', 10, 'todo', 2, '2026-03-01 00:48:17', NULL),
(6, 3, 'Chunk 1: tiny step', 'Auto-seeded chunk.', 10, 'todo', 3, '2026-03-01 00:48:17', NULL),
(7, 4, 'Chunk 2: tiny step', 'Auto-seeded chunk.', 10, 'done', 4, '2026-02-27 00:48:17', '2026-02-27 01:48:17'),
(1, 2, 'Open project + run dev server', 'Nur starten, keine Fixes.', 10, 'doing', 1, '2026-03-01 00:48:17', NULL),
(2, 2, 'Seed categories + skills', 'SQL laufen lassen, danach UI check.', 20, 'todo', 2, '2026-03-01 00:48:17', NULL),
(3, 2, 'Fix /todos mismatch', 'View oder Backend rename.', 15, 'todo', 3, '2026-03-01 00:48:17', NULL),
(4, 1, 'Chunk 2: tiny step', 'Auto-seeded chunk.', 10, 'todo', 1, '2026-02-28 00:48:17', NULL),
(5, 2, 'Chunk 3: tiny step', 'Auto-seeded chunk.', 10, 'todo', 2, '2026-03-01 00:48:17', NULL),
(6, 3, 'Chunk 1: tiny step', 'Auto-seeded chunk.', 10, 'todo', 3, '2026-03-01 00:48:17', NULL),
(7, 4, 'Chunk 2: tiny step', 'Auto-seeded chunk.', 10, 'done', 4, '2026-02-27 00:48:17', '2026-02-27 01:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `display_name` varchar(120) NOT NULL,
  `handle` varchar(80) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `display_name`, `handle`, `created_at`, `email`, `password_hash`, `updated_at`) VALUES
(2, 'LunaBow', NULL, '2026-03-01 16:20:17', 'lunamoser1337@gmail.com', '$2b$10$NeqPwXYIH3uLGhae8uvl3.AGSiQGyz0lO0gx.Hf7TTsbFVD0Zy1mq', '2026-03-03 01:52:28'),
(3, 'Luna Moser', NULL, '2026-03-02 15:03:25', 'mt231043@ustp-students.at', '$2b$10$ZroV/7wFflpkH3pSRSsXbuWIGX.8C7DwLfcxgrPKC5Q5IreM3aBFq', '2026-03-03 01:52:28'),
(2, 'LunaBow', NULL, '2026-03-01 16:20:17', 'lunamoser1337@gmail.com', '$2b$10$NeqPwXYIH3uLGhae8uvl3.AGSiQGyz0lO0gx.Hf7TTsbFVD0Zy1mq', '2026-03-03 01:52:28'),
(3, 'Luna Moser', NULL, '2026-03-02 15:03:25', 'mt231043@ustp-students.at', '$2b$10$ZroV/7wFflpkH3pSRSsXbuWIGX.8C7DwLfcxgrPKC5Q5IreM3aBFq', '2026-03-03 01:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_exercise_exclusions`
--

CREATE TABLE `user_exercise_exclusions` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `excluded_at` datetime DEFAULT current_timestamp(),
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_exercise_feedback`
--

CREATE TABLE `user_exercise_feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_run_id` bigint(20) UNSIGNED DEFAULT NULL,
  `helped` tinyint(1) NOT NULL,
  `effectiveness_rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `speed_of_effect` enum('instant','fast','medium','slow','unknown') DEFAULT 'unknown',
  `created_at` datetime DEFAULT current_timestamp(),
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_exercise_feedback`
--

INSERT INTO `user_exercise_feedback` (`id`, `user_id`, `exercise_id`, `exercise_run_id`, `helped`, `effectiveness_rating`, `speed_of_effect`, `created_at`, `comment`) VALUES
(1, 2, 3, 13, 1, 4, 'fast', '2026-02-26 12:02:52', 'Spürbarer Effekt.'),
(2, 2, 8, 12, 1, 4, 'fast', '2026-02-21 17:07:52', 'Spürbarer Effekt.'),
(3, 2, 9, 11, 1, 4, 'fast', '2026-03-01 18:08:52', 'Spürbarer Effekt.'),
(4, 2, 2, 10, 1, 4, 'fast', '2026-02-27 11:01:52', 'Spürbarer Effekt.'),
(5, 2, 5, 9, 1, 4, 'fast', '2026-02-24 14:04:52', 'Spürbarer Effekt.'),
(6, 2, 6, 8, 1, 4, 'fast', '2026-02-23 15:05:52', 'Spürbarer Effekt.'),
(7, 2, 7, 7, 1, 4, 'fast', '2026-02-22 16:06:52', 'Spürbarer Effekt.'),
(8, 2, 1, 6, 1, 4, 'fast', '2026-02-28 10:00:52', 'Spürbarer Effekt.'),
(9, 2, 5, 5, 1, 4, 'fast', '2026-02-25 19:11:52', 'Spürbarer Effekt.'),
(10, 2, 7, 4, 1, 3, 'medium', '2026-02-24 10:08:52', 'Minimal besser, aber besser.'),
(11, 2, 3, 3, 1, 3, 'medium', '2026-02-23 13:06:52', 'Minimal besser, aber besser.'),
(12, 2, 2, 2, 1, 3, 'medium', '2026-02-22 14:58:52', 'Minimal besser, aber besser.'),
(13, 2, 1, 1, 1, 5, 'fast', '2026-02-21 11:01:52', 'Hat mich echt runtergeholt.'),
(1, 2, 3, 13, 1, 4, 'fast', '2026-02-26 12:02:52', 'Spürbarer Effekt.'),
(2, 2, 8, 12, 1, 4, 'fast', '2026-02-21 17:07:52', 'Spürbarer Effekt.'),
(3, 2, 9, 11, 1, 4, 'fast', '2026-03-01 18:08:52', 'Spürbarer Effekt.'),
(4, 2, 2, 10, 1, 4, 'fast', '2026-02-27 11:01:52', 'Spürbarer Effekt.'),
(5, 2, 5, 9, 1, 4, 'fast', '2026-02-24 14:04:52', 'Spürbarer Effekt.'),
(6, 2, 6, 8, 1, 4, 'fast', '2026-02-23 15:05:52', 'Spürbarer Effekt.'),
(7, 2, 7, 7, 1, 4, 'fast', '2026-02-22 16:06:52', 'Spürbarer Effekt.'),
(8, 2, 1, 6, 1, 4, 'fast', '2026-02-28 10:00:52', 'Spürbarer Effekt.'),
(9, 2, 5, 5, 1, 4, 'fast', '2026-02-25 19:11:52', 'Spürbarer Effekt.'),
(10, 2, 7, 4, 1, 3, 'medium', '2026-02-24 10:08:52', 'Minimal besser, aber besser.'),
(11, 2, 3, 3, 1, 3, 'medium', '2026-02-23 13:06:52', 'Minimal besser, aber besser.'),
(12, 2, 2, 2, 1, 3, 'medium', '2026-02-22 14:58:52', 'Minimal besser, aber besser.'),
(13, 2, 1, 1, 1, 5, 'fast', '2026-02-21 11:01:52', 'Hat mich echt runtergeholt.');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `allow_gamification` tinyint(1) NOT NULL DEFAULT 1,
  `privacy_mode` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sarcastic_comments` tinyint(1) NOT NULL DEFAULT 0,
  `hand_drawn_mode` tinyint(1) NOT NULL DEFAULT 0,
  `leet_speak` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`user_id`, `allow_gamification`, `privacy_mode`, `updated_at`, `sarcastic_comments`, `hand_drawn_mode`, `leet_speak`) VALUES
(2, 1, 1, '2026-03-16 13:18:05', 0, 0, 0),
(2, 1, 1, '2026-03-16 13:32:49', 0, 0, 0),
(2, 1, 1, '2026-03-16 16:24:13', 0, 0, 0),
(2, 1, 1, '2026-03-16 16:30:25', 0, 1, 0),
(2, 1, 0, '2026-03-16 16:30:38', 1, 0, 1),
(2, 1, 1, '2026-03-16 16:30:50', 0, 0, 1),
(2, 1, 0, '2026-03-16 16:30:59', 0, 0, 0),
(2, 1, 0, '2026-03-16 16:55:58', 0, 0, 0),
(2, 1, 1, '2026-03-16 16:56:02', 0, 0, 1),
(2, 1, 1, '2026-03-16 16:56:06', 0, 0, 1),
(2, 1, 1, '2026-03-16 16:59:23', 0, 1, 0),
(2, 1, 0, '2026-03-16 16:59:28', 0, 0, 0),
(2, 1, 0, '2026-03-16 16:59:41', 0, 0, 0),
(2, 1, 0, '2026-03-18 13:14:00', 0, 0, 0),
(2, 1, 0, '2026-03-18 13:14:40', 0, 0, 0),
(2, 1, 0, '2026-03-18 13:16:40', 0, 0, 0),
(2, 1, 0, '2026-03-18 13:16:49', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_tag_exclusions`
--

CREATE TABLE `user_tag_exclusions` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `excluded_at` datetime DEFAULT current_timestamp(),
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_categories_name` (`name`);

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_checkins_user_date` (`user_id`,`date`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_exercises_skill_name` (`skill_id`,`name`),
  ADD KEY `idx_exercises_skill` (`skill_id`);

--
-- Indexes for table `exercise_runs`
--
ALTER TABLE `exercise_runs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_exercise_runs_user_time` (`user_id`,`started_at`),
  ADD KEY `idx_exercise_runs_exercise_time` (`exercise_id`,`started_at`);

--
-- Indexes for table `exercise_tags`
--
ALTER TABLE `exercise_tags`
  ADD PRIMARY KEY (`exercise_id`,`tag_id`),
  ADD KEY `idx_exercise_tags_tag` (`tag_id`);

--
-- Indexes for table `habits`
--
ALTER TABLE `habits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_habits_user` (`user_id`);

--
-- Indexes for table `habit_logs`
--
ALTER TABLE `habit_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_habit_date` (`habit_id`,`done_date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `habits`
--
ALTER TABLE `habits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `habit_logs`
--
ALTER TABLE `habit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
