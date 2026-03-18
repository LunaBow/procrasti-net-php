<?php
declare(strict_types=1);

namespace Repos;

final class CategoryRepo {
    public function __construct(private \PDO $pdo) {}

    public function all(): array {
        $st = $this->pdo->prepare("SELECT id, name, description, color_code FROM categories ORDER BY id ASC");
        $st->execute();
        return $st->fetchAll();
    }

    public function find(int $id): ?array {
        $st = $this->pdo->prepare("SELECT id, name, description, color_code FROM categories WHERE id = ?");
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }
}
