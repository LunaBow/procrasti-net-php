<?php 
// app/src/Views/categories.php - Browse skills by category
$title = 'Categories'; 
?>

<div class="content">
    <h1>Categories</h1>
    <p class="muted" style="margin-bottom: 2rem;">Select an area you want to focus on.</p>
    
    <div class="category-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
        <?php foreach ($categories as $category): ?>
            <a href="?page=skills&category_id=<?= e((string)$category['id']) ?>" class="category-card" style="background-color: <?= e((string)$category['color_code'] ?: '#666') ?>; padding: 1.5rem; border-radius: var(--radius-md); text-decoration: none; color: white; transition: transform 0.2s ease; display: block;">
                <div class="category-card-content">
                    <h2 style="margin: 0 0 0.5rem 0; font-size: 1.25rem;"><?= e((string)$category['name']) ?></h2>
                    <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;"><?= e((string)$category['description']) ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
.category-card:hover { transform: translateY(-5px); }
</style>
