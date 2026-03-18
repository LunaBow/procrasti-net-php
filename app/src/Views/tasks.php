<?php 
// If they have privacy mode on, we'll blur the text until they hover over it
$isPrivacyMode = ($settings['privacy_mode'] ?? 0) == 1;
$title = 'My Tasks'; 

// Group tasks by category
$tasksByCategory = [];
$uncategorizedTasks = [];

foreach ($tasks as $task) {
    // Handle both simple and full query results
    $categoryId = $task['category_id'] ?? null;
    $categoryName = $task['category_name'] ?? 'Uncategorized';
    $categoryColor = $task['color_code'] ?? '#666';

    if ($categoryId) {
        if (!isset($tasksByCategory[$categoryName])) {
            $tasksByCategory[$categoryName] = [
                'color' => $categoryColor,
                'tasks' => []
            ];
        }
        $tasksByCategory[$categoryName]['tasks'][] = $task;
    } else {
        $uncategorizedTasks[] = $task;
    }
}

// Get categories for the form
$categories = [
    ['id' => 1, 'name' => 'Focus', 'color_code' => '#4a5d50'],
    ['id' => 2, 'name' => 'Planning', 'color_code' => '#d68f7a'],
    ['id' => 3, 'name' => 'Regulation', 'color_code' => '#2d3e33'],
    ['id' => 4, 'name' => 'Energy', 'color_code' => '#e6d5c3'],
    ['id' => 5, 'name' => 'Survival', 'color_code' => '#6b7a71'],
    ['id' => 6, 'name' => 'Social', 'color_code' => '#8fa395'],
    ['id' => 7, 'name' => 'Mindset', 'color_code' => '#c47d68'],
    ['id' => 8, 'name' => 'ÖH', 'color_code' => '#ff9900'],
    ['id' => 9, 'name' => 'School', 'color_code' => '#3366cc'],
    ['id' => 10, 'name' => 'Life', 'color_code' => '#99cc33'],
    ['id' => 11, 'name' => 'Work', 'color_code' => '#cc3300'],
];
?>

<div class="content">
    <h1 data-lang="page-title-tasks">Today's Tasks</h1>
    <p class="muted" data-lang="page-subtitle-tasks">What's the next stupid easy step?</p>

    <form class="controls" method="post" action="?page=task_create" style="background: var(--surface-alt); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 2rem; overflow: hidden;">
        <input type="hidden" name="csrf" value="<?= e(Core\Csrf::token()) ?>">

        <div class="task-form-grid">
            <div class="field">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Task Title</label>
                <input type="text" name="title" class="item" placeholder="e.g. Open the document..." required>
            </div>

            <div class="field">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Category</label>
                <select name="category_id" style="background: var(--bg); border: 1px solid var(--border); color: var(--text); padding: 0.6rem; border-radius: var(--radius-sm); font-family: 'JetBrains Mono', monospace; width: 100%; min-width: 120px;">
                    <option value="">No Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" style="color: <?= e($category['color_code']) ?>;">
                            <?= e($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Due Date</label>
                <input type="date" name="due_date" style="background: var(--bg); border: 1px solid var(--border); color: var(--text); padding: 0.6rem; border-radius: var(--radius-sm); font-family: 'JetBrains Mono', monospace; width: 100%;">
            </div>

            <div class="field">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Reminder</label>
                <input type="datetime-local" name="reminder_at" style="background: var(--bg); border: 1px solid var(--border); color: var(--text); padding: 0.6rem; border-radius: var(--radius-sm); font-family: 'JetBrains Mono', monospace; width: 100%;">
            </div>
        </div>

        <button type="submit" class="pill" style="margin-top: 1rem; width: 100%;">Add Task</button>
    </form>

    <?php if (empty($tasks)): ?>
        <div class="empty">No tasks for today. Have a rest or start a tiny habit!</div>
    <?php else: ?>
        <!-- Uncategorized tasks -->
        <?php if (!empty($uncategorizedTasks)): ?>
            <div class="task-section">
                <h3 style="color: var(--text-muted); font-size: 1rem; margin-bottom: 1rem;">Uncategorized</h3>
                <ul class="todo-list" style="list-style: none; padding: 0;">
                    <?php foreach ($uncategorizedTasks as $t): ?>
                        <li class="todo-item <?= (($t['status'] ?? '') === 'done') ? 'done' : '' ?>" data-id="<?= (int)$t['id'] ?>">
                            <button class="toggleBtn" type="button" data-csrf="<?= e(Core\Csrf::token()) ?>" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;" data-lang="btn-toggle-task">
                                <?= (($t['status'] ?? '') === 'done') ? '✅' : '⬜' ?>
                            </button>
                            <div style="flex: 1;">
                                <span class="title <?= $isPrivacyMode ? 'privacy-blur' : '' ?>" style="transition: filter 0.2s ease;">
                                    <?= e((string)$t['title']) ?>
                                </span>
                                <?php if ($t['due_at']): ?>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                                        Due: <?= date('M j, Y', strtotime($t['due_at'])) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($t['reminder_at']): ?>
                                    <div style="font-size: 0.8rem; color: var(--accent); margin-top: 0.25rem;">
                                        🔔 Reminder: <?= date('M j, Y H:i', strtotime($t['reminder_at'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Categorized tasks -->
        <?php foreach ($tasksByCategory as $categoryName => $categoryData): ?>
            <div class="task-section" style="margin-top: 2rem;">
                <h3 style="color: <?= e($categoryData['color']) ?>; font-size: 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="width: 12px; height: 12px; background: <?= e($categoryData['color']) ?>; border-radius: 50%;"></span>
                    <?= e($categoryName) ?>
                </h3>
                <ul class="todo-list" style="list-style: none; padding: 0;">
                    <?php foreach ($categoryData['tasks'] as $t): ?>
                        <li class="todo-item <?= (($t['status'] ?? '') === 'done') ? 'done' : '' ?>" data-id="<?= (int)$t['id'] ?>">
                            <button class="toggleBtn" type="button" data-csrf="<?= e(Core\Csrf::token()) ?>" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;" data-lang="btn-toggle-task">
                                <?= (($t['status'] ?? '') === 'done') ? '✅' : '⬜' ?>
                            </button>
                            <div style="flex: 1;">
                                <span class="title <?= $isPrivacyMode ? 'privacy-blur' : '' ?>" style="transition: filter 0.2s ease;">
                                    <?= e((string)$t['title']) ?>
                                </span>
                                <?php if ($t['due_at']): ?>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                                        Due: <?= date('M j, Y', strtotime($t['due_at'])) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($t['reminder_at']): ?>
                                    <div style="font-size: 0.8rem; color: var(--accent); margin-top: 0.25rem;">
                                        🔔 Reminder: <?= date('M j, Y H:i', strtotime($t['reminder_at'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    /* If privacy mode is on, blur tasks so shoulder-surfers can't read them */
    .privacy-blur {
        filter: blur(5px);
        opacity: 0.7;
    }
    .privacy-blur:hover {
        filter: blur(0);
        opacity: 1;
    }

    .task-section {
        background: var(--surface-alt);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid var(--border);
    }

    .todo-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: var(--radius-sm);
        transition: background-color 0.2s ease;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .todo-item:last-child {
        border-bottom: none;
    }

    .todo-item:hover {
        background: rgba(0, 0, 0, 0.05);
    }

    .todo-item.done {
        opacity: 0.6;
    }

    .todo-item.done .title {
        text-decoration: line-through;
    }

    .task-form-grid {
        display: grid;
        grid-template-columns: 1fr auto auto auto;
        gap: 1rem;
        align-items: end;
    }

    .field {
        grid-column: span 1;
    }

    @media (max-width: 768px) {
        .task-form-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .field:nth-child(1) {
            grid-column: span 2;
        }
    }

    @media (max-width: 480px) {
        .task-form-grid {
            grid-template-columns: 1fr;
        }

        .field:nth-child(1) {
            grid-column: span 1;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle task completion
    document.querySelectorAll('.toggleBtn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const taskId = this.closest('.todo-item').dataset.id;
            const csrfToken = this.dataset.csrf;

            try {
                const response = await fetch('?page=task_toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        'csrf': csrfToken,
                        'id': taskId
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.status) {
                        const item = this.closest('.todo-item');
                        const title = item.querySelector('.title');

                        if (data.status === 'done') {
                            item.classList.add('done');
                            this.textContent = '✅';
                            if (title) {
                                title.style.textDecoration = 'line-through';
                            }
                        } else {
                            item.classList.remove('done');
                            this.textContent = '⬜';
                            if (title) {
                                title.style.textDecoration = 'none';
                            }
                        }
                    }
                }
            } catch (error) {
                console.error('Error toggling task:', error);
            }
        });
    });
});
</script>
