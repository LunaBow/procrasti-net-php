<?php 
// app/src/Views/habits.php - Page to track simple habits
$title = 'Habit Tracker'; 
?>

<div class="content panel" style="margin: 0 1.5rem; max-width: none;">
    <h2 style="font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: -1px; color: var(--primary); margin-top: 0; margin-bottom: 2rem;">Habit Tracker</h2>

    <!-- Form to create a new habit -->
    <form method="post" action="?page=habit_create" class="create-form" style="display: flex; gap: 1rem; margin-bottom: 3rem; background: rgba(0, 0, 0, 0.2); padding: 1rem; border-radius: var(--radius-md); border: 1px dashed var(--border);">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="text" name="name" placeholder="New habit name..." required style="flex: 1; padding: 0.75rem 1rem; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); font-family: inherit;">
        <button type="submit" style="padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); border: 0; background: var(--primary); color: white; font-weight: 800; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; cursor: pointer;">Add Habit</button>
    </form>

    <div class="grid habit-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem;">
        <?php if (empty($habits)): ?>
            <p>No habits yet. Start one above!</p>
        <?php else: ?>
            <!-- Loop through each habit and show a "card" for it -->
            <?php foreach ($habits as $h): ?>
                <article class="card" style="padding: 1.5rem; background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); border: 1px solid var(--border); display: flex; flex-direction: column; justify-content: space-between;">
                    <h3 style="margin: 0; font-size: 1.1rem; color: var(--text);"><?= e((string)$h['name']) ?></h3>
                    
                    <p class="streak" style="margin: 1rem 0 1.5rem; font-size: 0.85rem; font-family: 'JetBrains Mono', monospace; color: var(--text-muted); text-transform: uppercase;">
                        Total completions: <strong style="color: var(--primary); font-size: 1.2rem; margin-left: 0.5rem;"><?= (int)($h['streak'] ?? 0) ?></strong>
                    </p>
                    
                    <!-- AI Help: The data- attributes here are used by app.js to send an AJAX request when clicked. -->
                    <button type="button" class="check-btn habit-check-btn" data-id="<?= (int)$h['id'] ?>" data-csrf="<?= e(csrf_token()) ?>" style="width: 100%; padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--primary); background: transparent; color: var(--primary); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; cursor: pointer;">
                        Mark Done Today
                    </button>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>