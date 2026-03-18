<?php
declare(strict_types=1);

namespace Repos;

final class SkillRepo {
    public function __construct(private \PDO $pdo) {}

    public function all(): array {
        $st = $this->pdo->prepare("
            SELECT s.id, s.name, s.description, s.difficulty_level, s.energy_required, c.name as category_name
            FROM skills s
            JOIN categories c ON s.category_id = c.id
            ORDER BY s.name ASC
        ");
        $st->execute();
        return $st->fetchAll();
    }

    public function byCategory(int $categoryId): array {
        $st = $this->pdo->prepare("
            SELECT s.id, s.name, s.description, s.difficulty_level, s.energy_required, c.name as category_name
            FROM skills s
            JOIN categories c ON s.category_id = c.id
            WHERE s.category_id = ?
            ORDER BY s.name ASC
        ");
        $st->execute([$categoryId]);
        return $st->fetchAll();
    }

    public function find(int $id): ?array {
        $st = $this->pdo->prepare("SELECT * FROM skills WHERE id = ?");
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }
    
    public function pdo(): \PDO {
        return $this->pdo;
    }
}
