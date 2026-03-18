<?php
declare(strict_types=1);

namespace Repos;

use PDO;

final class SettingsRepo {
    public function __construct(private PDO $pdo) {}

    public function getSettings(int $userId): array {
        $st = $this->pdo->prepare("SELECT * FROM user_settings WHERE user_id = ?");
        $st->execute([$userId]);
        $row = $st->fetch(PDO::FETCH_ASSOC) ?: [];

        // Defaults for everyone, ensuring new settings don't break old users
        $defaults = [
            'user_id' => $userId,
            'allow_gamification' => 1,
            'privacy_mode' => 0,
            'sarcastic_comments' => 0,
            'hand_drawn_mode' => 0,
            'leet_speak' => 0,
        ];

        return array_merge($defaults, $row);
    }

    public function updateSettings(int $userId, int $gamification, int $privacy, int $sarcasm, int $handDrawn, int $leet): void {
        $st = $this->pdo->prepare("
            INSERT INTO user_settings (user_id, allow_gamification, privacy_mode, sarcastic_comments, hand_drawn_mode, leet_speak)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                allow_gamification = VALUES(allow_gamification),
                privacy_mode = VALUES(privacy_mode),
                sarcastic_comments = VALUES(sarcastic_comments),
                hand_drawn_mode = VALUES(hand_drawn_mode),
                leet_speak = VALUES(leet_speak)
        ");
        $st->execute([$userId, $gamification, $privacy, $sarcasm, $handDrawn, $leet]);
    }

    public function getUserInfo(int $userId): array {
        $st = $this->pdo->prepare("SELECT id, display_name, email FROM users WHERE id = ?");
        $st->execute([$userId]);
        return $st->fetch() ?: [];
    }

    public function updateDisplayName(int $userId, string $name): void {
        $st = $this->pdo->prepare("UPDATE users SET display_name = ? WHERE id = ?");
        $st->execute([trim($name), $userId]);
    }
}
