<?php
declare(strict_types=1);

namespace Repos;

final class HabitRepo {
    public function __construct(private \PDO $pdo) {}

    public function allByUser(int $userId): array {
        // Fetch all habits and their logs to calculate streaks
        $st = $this->pdo->prepare("
            SELECT h.id, h.name, GROUP_CONCAT(hl.done_date ORDER BY hl.done_date DESC) as done_dates
            FROM habits h
            LEFT JOIN habit_logs hl ON h.id = hl.habit_id
            WHERE h.user_id = ?
            GROUP BY h.id
            ORDER BY h.id DESC
        ");
        $st->execute([$userId]);
        $rows = $st->fetchAll();

        // Calculate streaks
        foreach ($rows as &$row) {
            $row['streak'] = $this->calculateStreak($row['done_dates']);
        }
        return $rows;
    }

    public function create(int $userId, string $name): void {
        $st = $this->pdo->prepare("INSERT INTO habits (user_id, name) VALUES (?, ?)");
        $st->execute([$userId, trim($name)]);
    }

    public function check(int $userId, int $habitId, string $date): bool {
        // Verify habit belongs to user
        $st = $this->pdo->prepare("SELECT id FROM habits WHERE id = ? AND user_id = ?");
        $st->execute([$habitId, $userId]);
        if (!$st->fetch()) {
            return false;
        }

        // Insert or ignore if already checked today
        $st = $this->pdo->prepare("INSERT IGNORE INTO habit_logs (habit_id, done_date) VALUES (?, ?)");
        $st->execute([$habitId, $date]);
        return true;
    }

    private function calculateStreak(?string $datesStr): int {
        if (!$datesStr) return 0;
        
        $dates = explode(',', $datesStr);
        $streak = 0;
        $currentDate = new \DateTime();
        
        // Normalize time to midnight
        $currentDate->setTime(0, 0, 0);

        foreach ($dates as $dateStr) {
            $logDate = new \DateTime($dateStr);
            $logDate->setTime(0, 0, 0);
            
            $diff = $currentDate->diff($logDate)->days;
            
            // Allow checking today or yesterday
            if ($diff === 0 && $streak === 0) {
                 // checked today
                 $streak++;
                 $currentDate->modify('-1 day');
            } elseif ($diff === 1) {
                // checked yesterday (relative to current check)
                $streak++;
                $currentDate->modify('-1 day');
            } elseif ($diff > 1) {
                // streak broken
                break;
            }
        }
        return $streak;
    }
}
