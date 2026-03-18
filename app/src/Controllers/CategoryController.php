<?php
declare(strict_types=1);

namespace Controllers;

use Repos\CategoryRepo;

final class CategoryController {
    public function __construct(private CategoryRepo $repo) {}

    public function index(): void {
        $categories = $this->repo->all();
        $content = __DIR__ . '/../Views/categories.php';
        require __DIR__ . '/../Views/layout.php';
    }
}
