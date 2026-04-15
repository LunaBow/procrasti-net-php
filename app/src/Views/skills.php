<?php 
// app/src/Views/skills.php - Database of helpful skills and techniques
$title = 'Skill Database'; 
?>

<div class="content panel" style="margin: 0 1.5rem; max-width: none;">
    <h2 style="font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: -1px; color: var(--primary); margin-top: 0; margin-bottom: 2rem;">Skill Database</h2>
    
    <!-- Filtering controls (handled by JavaScript in app.js) -->
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
        
        <button type="button" id="reset-skills-btn" style="background: var(--surface-alt); color: var(--text); border: 1px solid var(--border); padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); font-weight: 800; text-transform: uppercase; font-size: 0.8rem; cursor: pointer;">Reset</button>
    </div>

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <?php foreach ($skills as $skill): ?>
            <!-- AI Help: The data- attributes here allow the JavaScript to hide/show cards based on search/filter. -->
            <article class="card skill-card-item" data-category="<?= e((string)$skill['category_name']) ?>" data-name="<?= e((string)$skill['name']) ?>" style="background: var(--surface-alt); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.5rem;">
                <h3 style="margin-bottom: 0.5rem; font-size: 1.1rem;"><?= e((string)$skill['name']) ?></h3>
                <p class="muted" style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1rem;"><?= e((string)$skill['description']) ?></p>
                
                <div class="tags" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <!-- Difficulty Level Tag -->
                    <span class="tag" style="display: inline-block; padding: 0.2rem 0.75rem; border-radius: 999px; background: var(--bg); border: 1px solid var(--border); font-size: 0.75rem; font-weight: 600; text-transform: lowercase;">
                        <?= e($skill['difficulty_level'] <= 1 ? 'easy' : ($skill['difficulty_level'] == 2 ? 'medium' : 'hard')) ?>
                    </span>
                    
                    <!-- Category Tag -->
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

<script>
// Reset button functionality
document.getElementById('reset-skills-btn')?.addEventListener('click', () => {
    const search = document.getElementById('skill-search-input');
    const category = document.getElementById('skill-category-select');
    if (search) search.value = '';
    if (category) category.value = '';
    // Trigger input event to refresh list
    search?.dispatchEvent(new Event('input'));
});
</script>