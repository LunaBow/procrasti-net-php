<?php
declare(strict_types=1);

namespace Controllers;

use Repos\SkillRepo;
use Repos\CategoryRepo;

final class SkillController {
    public function __construct(private SkillRepo $repo) {}

    public function index(): void {
        $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
        if ($categoryId) {
            $skills = $this->repo->byCategory($categoryId);
            $category = (new CategoryRepo($this->repo->pdo()))->find($categoryId);
        } else {
            $skills = $this->repo->all();
        }
        $content = __DIR__ . '/../Views/skills.php';
        require __DIR__ . '/../Views/layout.php';
    }
}
