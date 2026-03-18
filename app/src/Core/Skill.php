<?php
declare(strict_types=1);

namespace Core;

final class Skill {
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly int $categoryId,
        public readonly int $difficultyLevel,
        public readonly string $energyRequired,
    ) {}
}
