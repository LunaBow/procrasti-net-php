<?php
declare(strict_types=1);

namespace Core;

final class Category {
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?string $colorCode,
    ) {}
}
