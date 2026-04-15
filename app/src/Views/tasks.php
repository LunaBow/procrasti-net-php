<?php 
// app/src/Views/tasks.php - The main task list page

$isPrivacyMode = ($settings['privacy_mode'] ?? 0) == 1;
$title = 'My Tasks';

// AI Help: usort sorts an array using a custom comparison function.
// Here we sort by the date the task is due, or when it was created.
usort($tasks, function($a, $b) {
    $aDate = strtotime($a['due_at'] ?? $a['created_at']);
    $bDate = strtotime($b['due_at'] ?? $b['created_at']);
    return $aDate - $bDate;
});

// AI Help: We group tasks by category name to display them in sections.
$tasksByCategory = [];
$uncategorizedTasks = [];

foreach ($tasks as $task) {
    $categoryId = $task['category_id'] ?? null;
    $categoryName = $task['category_name'] ?? 'Uncategorized';
    $categoryColor = $task['color_code'] ?? '#666';

    if ($categoryId) {
        if (!isset($tasksByCategory[$categoryName])) {
            $tasksByCategory[$categoryName] = [
                'id' => $categoryId,
                'color' => $categoryColor,
                'tasks' => []
            ];
        }
        $tasksByCategory[$categoryName]['tasks'][] = $task;
    } else {
        $uncategorizedTasks[] = $task;
    }
}

// Stats calculation
$todoCount = count(array_filter($tasks, fn($t) => ($t['status'] ?? '') === 'todo'));
$doneCount = count(array_filter($tasks, fn($t) => ($t['status'] ?? '') === 'done'));
$totalCount = count($tasks);
?>

<div class="content">
    <h1 data-lang="page-title-tasks">Today's Tasks</h1>
    <p class="muted" data-lang="page-subtitle-tasks">What's the next stupid easy step?</p>

    <!-- Stats Bar -->
    <div class="task-stats">
        <div class="stat">
            <span class="stat-value"><?= $todoCount ?></span>
            <span class="stat-label">To Do</span>
        </div>
        <div class="stat">
            <span class="stat-value"><?= $doneCount ?></span>
            <span class="stat-label">Done</span>
        </div>
        <div class="stat">
            <span class="stat-value"><?= $totalCount ?></span>
            <span class="stat-label">Total</span>
        </div>
    </div>

    <!-- Create Task Form -->
    <form class="task-form" method="post" action="?page=task_create">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

        <div class="task-form-row">
            <div class="field field-title">
                <label data-lang="label-task-title">Task Title</label>
                <input type="text" name="title" placeholder="e.g. Open the document..." required data-lang-placeholder="placeholder-task">
            </div>
            <div class="field field-category">
                <label data-lang="label-category">Category</label>
                <select name="category_id">
                    <option value="" data-lang="option-no-category">No Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="task-form-row">
            <div class="field field-date">
                <label data-lang="label-due-date">Due Date</label>
                <input type="date" name="due_date">
            </div>
            <div class="field field-reminder">
                <label data-lang="label-reminder">Reminder</label>
                <input type="datetime-local" name="reminder_at">
            </div>
        </div>

        <button type="submit" class="pill" data-lang="btn-add-task">Add Task</button>
    </form>

    <!-- Filter & Sort Controls -->
    <div class="task-controls">
        <div class="control-group">
            <label>Filter:</label>
            <div class="category-filters">
                <?php foreach ($tasksByCategory as $catName => $catData): ?>
                    <button type="button" class="category-filter-btn" data-category="<?= htmlspecialchars($catName) ?>" style="border-color: <?= $catData['color'] ?>; color: <?= $catData['color'] ?>;">
                        <?= e($catName) ?>
                    </button>
                <?php endforeach; ?>
                <button type="button" class="category-filter-btn" data-category="show-all">Show All</button>
                <button type="button" class="category-filter-btn" data-category="hide-completed">Hide Done</button>
            </div>
        </div>
    </div>

    <!-- Tasks Display -->
    <?php if (empty($tasks)): ?>
        <div class="empty" data-lang="no-tasks-today">No tasks for today. Have a rest or start a tiny habit!</div>
    <?php else: ?>
        
        <!-- Uncategorized Tasks Section -->
        <?php if (!empty($uncategorizedTasks)): ?>
            <div class="task-section" data-category="Uncategorized">
                <div class="section-header">
                    <h3>Uncategorized</h3>
                    <span class="task-count"><?= count($uncategorizedTasks) ?></span>
                </div>
                <div class="task-list">
                    <?php foreach ($uncategorizedTasks as $task): ?>
                        <?= renderTaskItem($task, $isPrivacyMode) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Categorized Tasks Sections -->
        <?php foreach ($tasksByCategory as $categoryName => $categoryData): ?>
            <div class="task-section" data-category="<?= htmlspecialchars($categoryName) ?>">
                <div class="section-header" style="border-left: 4px solid <?= $categoryData['color'] ?>;">
                    <h3 style="color: <?= $categoryData['color'] ?>;">
                        <span class="category-dot" style="background: <?= $categoryData['color'] ?>;"></span>
                        <?= e($categoryName) ?>
                    </h3>
                    <span class="task-count"><?= count($categoryData['tasks']) ?></span>
                </div>
                <div class="task-list">
                    <?php foreach ($categoryData['tasks'] as $task): ?>
                        <?= renderTaskItem($task, $isPrivacyMode) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
/**
 * Helper function to render a single task item to keep the HTML cleaner
 */
function renderTaskItem($task, $isPrivacyMode) {
    ob_start(); // AI Help: Start output buffering to capture the HTML in a string
    ?>
    <div class="task-item <?= ($task['status'] ?? '') === 'done' ? 'done' : '' ?>" data-task-id="<?= (int)$task['id'] ?>" data-status="<?= $task['status'] ?? 'todo' ?>">
        <form method="post" action="?page=task_toggle" class="task-toggle-form" style="display: contents;">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
            <button type="submit" class="task-checkbox">
                <?= ($task['status'] ?? '') === 'done' ? '✅' : '⬜' ?>
            </button>
        </form>
        <div class="task-content">
            <span class="task-title <?= $isPrivacyMode ? 'privacy-blur' : '' ?>">
                <?= e((string)$task['title']) ?>
            </span>
            <div class="task-meta">
                <?php if ($task['due_at']): ?>
                    <span>📅 <?= date('M j, Y', strtotime($task['due_at'])) ?></span>
                <?php endif; ?>
                <?php if ($task['reminder_at']): ?>
                    <span>🔔 <?= date('M j H:i', strtotime($task['reminder_at'])) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <form method="post" action="?page=task_delete" class="task-delete-form" style="display: contents;" onsubmit="return confirm('Delete this task?');">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
            <button type="submit" class="task-delete" title="Delete task">✕</button>
        </form>
    </div>
    <?php
    return ob_get_clean(); // Capture the buffer and return as string
}
?>

<style>
    /* ... existing styles kept but simplified ... */
    .task-stats { display: flex; gap: 1.5rem; margin-bottom: 2rem; justify-content: center; flex-wrap: wrap; }
    .stat { display: flex; flex-direction: column; align-items: center; background: var(--surface); padding: 1.25rem 2rem; border-radius: var(--radius-lg); border: 2px solid var(--border); min-width: 120px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .stat-value { font-size: 2.2rem; font-weight: 900; color: var(--primary); }
    .stat-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-top: 0.5rem; }
    .task-form { background: var(--surface); padding: 2rem; border-radius: var(--radius-lg); border: 2px solid var(--border); margin-bottom: 2rem; }
    .task-form input, .task-form select { padding: 0.75rem 1rem; border: 2px solid var(--border); border-radius: var(--radius-md); background: var(--surface); color: var(--text); }
    .task-controls { background: var(--surface); padding: 1.5rem; border-radius: var(--radius-lg); border: 2px solid var(--border); margin-bottom: 2rem; display: flex; gap: 2rem; align-items: center; }
    .category-filter-btn { padding: 0.6rem 1.2rem; border: 2px solid; border-radius: var(--radius-lg); background: transparent; cursor: pointer; font-weight: 700; }
    .category-filter-btn.active { background: currentColor; color: var(--surface); }
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding: 1rem 0; border-bottom: 3px solid var(--border); }
    .section-header h3 { display: flex; align-items: center; gap: 0.75rem; margin: 0; font-size: 1.25rem; }
    .category-dot { display: inline-block; width: 14px; height: 14px; border-radius: 50%; }
    .task-count { background: var(--primary); color: white; padding: 0.4rem 1rem; border-radius: var(--radius-lg); font-size: 0.85rem; font-weight: 800; }
    .task-list { display: flex; flex-direction: column; gap: 1rem; }
    .task-item { display: flex; align-items: flex-start; gap: 1rem; padding: 1.25rem; background: var(--surface); border-radius: var(--radius-lg); border: 2px solid var(--border); transition: all 0.3s ease; }
    .task-item.done { opacity: 0.65; background: var(--surface-alt); }
    .task-checkbox { background: none; border: none; font-size: 1.8rem; cursor: pointer; }
    .task-title { display: block; font-weight: 700; word-break: break-word; font-size: 1.05rem; }
    .task-item.done .task-title { text-decoration: line-through; color: var(--text-muted); }
    .task-meta { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem; display: flex; gap: 1rem; }
    .task-delete { background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--text-muted); }
    .privacy-blur { filter: blur(5px); transition: filter 0.2s ease; }
    .privacy-blur:hover { filter: blur(0); }
    
    @media (max-width: 768px) {
        .task-controls { flex-direction: column; align-items: flex-start; }
    }
</style>

<script>
// Client-side filtering and stats update
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.category-filter-btn');
    const sections = document.querySelectorAll('.task-section');
    let activeFilter = null;
    let hideCompleted = false;

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            if (category === 'show-all') {
                activeFilter = null;
                hideCompleted = false;
                filterBtns.forEach(b => b.classList.remove('active'));
            } else if (category === 'hide-completed') {
                hideCompleted = !hideCompleted;
                this.classList.toggle('active');
            } else {
                if (activeFilter === category) {
                    activeFilter = null;
                    this.classList.remove('active');
                } else {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    activeFilter = category;
                    this.classList.add('active');
                }
            }
            applyFilters();
        });
    });

    function applyFilters() {
        sections.forEach(section => {
            let showSection = (activeFilter === null || section.dataset.category === activeFilter);
            section.style.display = showSection ? 'block' : 'none';

            if (showSection) {
                section.querySelectorAll('.task-item').forEach(item => {
                    item.style.display = (hideCompleted && item.classList.contains('done')) ? 'none' : 'flex';
                });
            }
        });
    }

    // AI Help: This handles clicking the checkbox without reloading the page.
    document.querySelectorAll('.task-toggle-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(this.action, { method: 'POST', body: new FormData(this) })
            .then(res => res.json())
            .then(data => {
                const item = this.closest('.task-item');
                const box = this.querySelector('.task-checkbox');
                if (data.status === 'done') {
                    item.classList.add('done');
                    box.textContent = '✅';
                } else {
                    item.classList.remove('done');
                    box.textContent = '⬜';
                }
                updateStats();
            });
        });
    });

    function updateStats() {
        document.querySelectorAll('.stat-value')[0].textContent = document.querySelectorAll('.task-item:not(.done)').length;
        document.querySelectorAll('.stat-value')[1].textContent = document.querySelectorAll('.task-item.done').length;
        document.querySelectorAll('.stat-value')[2].textContent = document.querySelectorAll('.task-item').length;
    }
});
</script>
