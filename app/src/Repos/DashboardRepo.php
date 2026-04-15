<?php

declare(strict_types=1);

namespace Repos;

final class DashboardRepo
{
    public function __construct(private \PDO $pdo)
    {
    }

    public function all(): array
    {
        // Fetch all dashboard records in ascending ID order.
        $stmt = $this->pdo->prepare(
            'SELECT id, name, description, color_code
             FROM dashboard
             ORDER BY id ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        // Fetch one dashboard record by ID.
        $stmt = $this->pdo->prepare(
            'SELECT id, name, description, color_code
             FROM dashboard
             WHERE id = ?'
        );
        $stmt->execute([$id]);

        $row = $stmt->fetch();

        return $row ?: null;
    }
}
