<?php
declare(strict_types=1);

namespace Repos;

final class TaskRepo {
    public function __construct(private \PDO $pdo) {}

    // Grab all tasks for a specific user, ordered newest first
    public function allByUser(int $userId): array {
        $st = $this->pdo->prepare("
            SELECT t.id, t.title, t.status, t.energy_required, t.due_at, t.created_at, t.category_id, t.reminder_at,
                   c.name as category_name, c.color_code
            FROM tasks t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = ?
            ORDER BY t.id DESC
        ");
        $st->execute([$userId]);
        return $st->fetchAll();
    }
    
    // Find tasks that are due on a specific date (or created then, if no due date). Used by the Planner.
    public function byDate(int $userId, string $date): array {
        $st = $this->pdo->prepare("
            SELECT id, title, status, energy_required, due_at, created_at
            FROM tasks
            WHERE user_id = ? 
            AND DATE(COALESCE(due_at, created_at)) = ?
            ORDER BY id DESC
        ");
        $st->execute([$userId, $date]);
        return $st->fetchAll();
    }

    // Find all tasks within a date range. Used to generate the .ics calendar export.
    public function byDateRange(int $userId, string $fromDate, string $toDate): array {
        $st = $this->pdo->prepare("
            SELECT id, title, status, energy_required, due_at, created_at
            FROM tasks
            WHERE user_id = ? 
            AND DATE(COALESCE(due_at, created_at)) BETWEEN ? AND ?
            ORDER BY id ASC
        ");
        $st->execute([$userId, $fromDate, $toDate]);
        return $st->fetchAll();
    }

    // Toss a new task into the pile
    public function create(int $userId, string $title, ?int $categoryId = null, ?string $dueDate = null, ?string $reminderAt = null): int {
        $st = $this->pdo->prepare("INSERT INTO tasks (user_id, title, status, category_id, due_at, reminder_at) VALUES (?, ?, 'todo', ?, ?, ?)");
        $st->execute([$userId, $title, $categoryId, $dueDate, $reminderAt]);
        return (int)$this->pdo->lastInsertId();
    }


    // Switch a task between 'done' and 'todo'
    public function toggleDone(int $userId, int $taskId): array {
        // First check what status it's currently at
        $st = $this->pdo->prepare("SELECT status FROM tasks WHERE id = ? AND user_id = ?");
        $st->execute([$taskId, $userId]);
        $row = $st->fetch();

        if (!$row) {
            http_response_code(404);
            return ['error' => 'Not found'];
        }

        // Flip it
        $current = (string)$row['status'];
        $new = ($current === 'done') ? 'todo' : 'done';

        // Update it in the DB, and set the completed_at timestamp if we just finished it
        $up = $this->pdo->prepare("
            UPDATE tasks
            SET status = ?, completed_at = CASE WHEN ? = 'done' THEN NOW() ELSE NULL END
            WHERE id = ? AND user_id = ?
        ");
        $up->execute([$new, $new, $taskId, $userId]);

        // Return the new status so the frontend JS knows what to update the UI to
        return ['id' => $taskId, 'status' => $new];
    }
}