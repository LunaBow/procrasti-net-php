<?php $title = 'Categories'; ?>

<div class="content">
    <h1>Categories</h1>
    <p class="muted" style="margin-bottom: 2rem;">Select an area you want to focus on.</p>
    
    <div class="category-grid">
        <?php foreach ($categories as $category): ?>
            <a href="/?page=skills&category_id=<?= e((string)$category['id']) ?>" class="category-card" style="background-color: <?= e((string)$category['color_code']) ?>">
                <div class="category-card-content">
                    <h2><?= e((string)$category['name']) ?></h2>
                    <p><?= e((string)$category['description']) ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
