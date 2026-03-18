<?php
declare(strict_types=1);

namespace Controllers;

use Repos\CategoryRepo;

final class CategoryController {
    public function __construct(private CategoryRepo $repo) {}

    public function index(): void {
        $categories = $this->repo->all();
        // Use the global render function instead of manual requires!
        render('categories', ['categories' => $categories]);
    }
}