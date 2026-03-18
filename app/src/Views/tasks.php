<?php 
// If they have privacy mode on, we'll blur the text until they hover over it
$isPrivacyMode = ($settings['privacy_mode'] ?? 0) == 1;
$title = 'My Tasks'; 

// Group tasks by category
$tasksByCategory = [];
$uncategorizedTasks = [];

foreach ($tasks as $task) {
    if ($task['category_id']) {
        $categoryName = $task['category_name'] ?? 'Unknown';
        if (!isset($tasksByCategory[$categoryName])) {
            $tasksByCategory[$categoryName] = [
                'color' => $task['color_code'] ?? '#666',
                'tasks' => []
            ];
        }
        $tasksByCategory[$categoryName]['tasks'][] = $task;
    } else {
        $uncategorizedTasks[] = $task;
    }
}
?>

<div class="content">
    <h1 data-lang="page-title-tasks">Today's Tasks</h1>
    <p class="muted" data-lang="page-subtitle-tasks">What's the next stupid easy step?</p>

    <form class="controls" method="post" action="?page=task_create">
        <input type="hidden" name="csrf" value="<?= e(Core\Csrf::token()) ?>">
        <div class="field" style="flex: 1;">
            <input type="text" name="title" class="item" placeholder="e.g. Open the document..." required style="width: 100%;" data-lang="placeholder-task">
        </div>
        <select name="category_id" style="background: var(--bg); border: 1px solid var(--border); color: var(--text); padding: 0.6rem; border-radius: var(--radius-sm); font-family: 'JetBrains Mono', monospace;">
            <option value="" data-lang="label-category">All categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" style="color: <?= e($category['color_code'] ?? '#666') ?>;">
                    <?= e($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="pill" data-lang="btn-add-task">Add Task</button>
    </form>

    <?php if (empty($tasks)): ?>
        <div class="empty" data-lang="no-tasks-today">No tasks today. Lucky you.</div>
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
                            <span class="title <?= $isPrivacyMode ? 'privacy-blur' : '' ?>" style="transition: filter 0.2s ease;">
                                <?= e((string)$t['title']) ?>
                            </span>
                            <button class="deleteBtn" type="button" data-id="<?= (int)$t['id'] ?>" data-csrf="<?= e(Core\Csrf::token()) ?>" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--text-muted); margin-left: auto;" data-lang="btn-delete-task">✕</button>
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
                            <span class="title <?= $isPrivacyMode ? 'privacy-blur' : '' ?>" style="transition: filter 0.2s ease;">
                                <?= e((string)$t['title']) ?>
                            </span>
                            <button class="deleteBtn" type="button" data-id="<?= (int)$t['id'] ?>" data-csrf="<?= e(Core\Csrf::token()) ?>" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--text-muted); margin-left: auto;" data-lang="btn-delete-task">✕</button>
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
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: var(--radius-sm);
        transition: background-color 0.2s ease;
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

    .deleteBtn:hover {
        color: var(--accent);
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

    // Delete task
    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', async function() {
            if (!confirm('Are you sure you want to delete this task?')) {
                return;
            }

            const taskId = this.dataset.id;
            const csrfToken = this.dataset.csrf;

            try {
                const response = await fetch('?page=task_delete', {
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
                    if (data.success) {
                        this.closest('.todo-item').remove();
                    }
                }
            } catch (error) {
                console.error('Error deleting task:', error);
            }
        });
    });
});
</script>
