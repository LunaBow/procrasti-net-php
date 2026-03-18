<?php 
$title = 'Skill Database - Procrasti-php'; 
// If accessed directly via ?page=skills, it should show all skills with JS filters
// If accessed via ?page=skills&category_id=..., it pre-filters them.
?>

<div class="content panel" style="margin: 0 1.5rem; max-width: none;">
    <h2 style="font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: -1px; color: var(--primary); margin-top: 0; margin-bottom: 2rem;">Skill Database</h2>
    
    <!-- Controls exactly matching Astro Node mock -->
    <div class="controls" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <input type="text" id="skill-search-input" placeholder="Search skills (e.g. breathing, grounding)" style="flex: 1; padding: 0.75rem 1rem; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); font-family: inherit;">
        
        <select id="skill-category-select" style="padding: 0.75rem 1rem; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); font-family: inherit;">
            <option value="" selected>All categories</option>
            <option value="Energy">Energy</option>
            <option value="Focus">Focus</option>
            <option value="Mindset">Mindset</option>
            <option value="Planning">Planning</option>
            <option value="Regulation">Regulation</option>
            <option value="Social">Social</option>
            <option value="Survival">Survival</option>
        </select>
        
        <button type="button" onclick="document.getElementById('skill-search-input').value=''; document.getElementById('skill-category-select').value=''; document.getElementById('skill-search-input').dispatchEvent(new Event('input'));" style="background: var(--surface-alt); color: var(--text); border: 1px solid var(--border); padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); font-weight: 800; text-transform: uppercase; font-size: 0.8rem; cursor: pointer;">Reset</button>
    </div>

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <?php foreach ($skills as $skill): ?>
            <!-- Note the dataset tags for JS filtering -->
            <article class="card skill-card-item" data-category="<?= e((string)$skill['category_name']) ?>" data-name="<?= e((string)$skill['name']) ?>" style="background: var(--surface-alt); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.5rem;">
                <h3 style="margin-bottom: 0.5rem; font-size: 1.1rem;"><?= e((string)$skill['name']) ?></h3>
                <p class="muted" style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1rem;"><?= e((string)$skill['description']) ?></p>
                
                <div class="tags" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <span class="tag" style="display: inline-block; padding: 0.2rem 0.75rem; border-radius: 999px; background: var(--bg); border: 1px solid var(--border); font-size: 0.75rem; font-weight: 600; text-transform: lowercase;">
                        <?= e($skill['difficulty_level'] == 1 ? 'easy/5' : ($skill['difficulty_level'] == 2 ? 'medium/5' : 'hard/5')) ?>
                    </span>
                    
                    <?php if (isset($skill['category_name'])): ?>
                        <span class="tag" style="display: inline-block; padding: 0.2rem 0.75rem; border-radius: 999px; background: var(--primary); color: white; border: 1px solid var(--primary-hover); font-size: 0.75rem; font-weight: 600;">
                            <?= e((string)$skill['category_name']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>