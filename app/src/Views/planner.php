<?php 
// app/src/Views/planner.php - A daily view for tasks
$title = 'Daily Planner'; 
?>

<div class="content panel" style="margin: 0 1.5rem; max-width: none;">
    <h2 style="font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: -1px; color: var(--primary); margin-top: 0; margin-bottom: 2rem;">Daily Planner</h2>

    <!-- Form to pick a different date -->
    <form class="controls" method="get" action="index.php" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <input type="hidden" name="page" value="planner">
        <input type="date" name="date" value="<?= e($selectedDate) ?>" onchange="this.form.submit()" style="background: var(--bg); border: 1px solid var(--border); color: var(--text); padding: 0.6rem; border-radius: var(--radius-sm); font-family: 'JetBrains Mono', monospace;">
        <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); font-weight: 800; text-transform: uppercase; font-size: 0.8rem; cursor: pointer;">Refresh</button>
    </form>

    <!-- List of tasks for the selected day -->
    <ul class="todo-list" style="display: flex; flex-direction: column; gap: 0.75rem; padding: 0; list-style: none;">
        <?php if (empty($tasks)): ?>
            <li class="empty" style="text-align: center; color: var(--text-muted); font-style: italic; padding: 2rem;">Nothing scheduled for this day.</li>
        <?php else: ?>
            <?php foreach ($tasks as $t): ?>
                <li style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: var(--surface-alt); border-radius: var(--radius-md); gap: 1rem; border: 1px solid var(--border);">
                    <span class="title" style="flex: 1; font-weight: 600; color: <?= $t['status'] === 'done' ? 'var(--text-muted)' : 'var(--text)' ?>; text-decoration: <?= $t['status'] === 'done' ? 'line-through' : 'none' ?>;">
                        <?= e((string)$t['title']) ?>
                    </span>
                    <span class="pill" style="padding: 0.4rem 1rem; font-size: 0.75rem; background: <?= $t['status'] === 'done' ? 'var(--primary)' : 'var(--surface)' ?>; color: <?= $t['status'] === 'done' ? 'white' : 'var(--text-muted)' ?>; border: 1px solid <?= $t['status'] === 'done' ? 'var(--primary)' : 'var(--border)' ?>;">
                        <?= e(ucfirst((string)$t['status'])) ?>
                    </span>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
