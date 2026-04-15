<?php
declare(strict_types=1);

namespace Repos;

final class CategoryRepo
{
    public function __construct(private \PDO $pdo)
    {
    }

    public function all(): array
    {
        // Fetch all categories in ascending ID order.
        $stmt = $this->pdo->prepare(
            'SELECT id, name, description, color_code
             FROM categories
             ORDER BY id ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        // Fetch one category by ID.
        $stmt = $this->pdo->prepare(
            'SELECT id, name, description, color_code
             FROM categories
             WHERE id = ?'
        );
        $stmt->execute([$id]);

        $row = $stmt->fetch();

        return $row ?: null;
    }
}
