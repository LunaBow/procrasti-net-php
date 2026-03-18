<?php 
$isPrivacyMode = ($settings['privacy_mode'] ?? 0) == 1;
$title = 'My Tasks';

// Get categories
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

// Sort tasks by due date
usort($tasks, function($a, $b) {
    $aDate = strtotime($a['due_at'] ?? $a['created_at']);
    $bDate = strtotime($b['due_at'] ?? $b['created_at']);
    return $aDate - $bDate;
});

// Group by category
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

// Count stats
$todoCount = count(array_filter($tasks, fn($t) => ($t['status'] ?? '') === 'todo'));
$doneCount = count(array_filter($tasks, fn($t) => ($t['status'] ?? '') === 'done'));
$totalCount = count($tasks);
?>

<div class="content">
    <h1>Today's Tasks</h1>
    <p class="muted">What's the next stupid easy step?</p>

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
        <input type="hidden" name="csrf" value="<?= e(Core\Csrf::token()) ?>">

        <div class="task-form-row">
            <div class="field field-title">
                <label>Task Title</label>
                <input type="text" name="title" placeholder="e.g. Open the document..." required>
            </div>
            <div class="field field-category">
                <label>Category</label>
                <select name="category_id">
                    <option value="">No Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="task-form-row">
            <div class="field field-date">
                <label>Due Date</label>
                <input type="date" name="due_date">
            </div>
            <div class="field field-reminder">
                <label>Reminder</label>
                <input type="datetime-local" name="reminder_at">
            </div>
        </div>

        <button type="submit" class="pill">Add Task</button>
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

        <div class="control-group">
            <label>Sort by:</label>
            <select id="sort-select">
                <option value="due-date">Due Date</option>
                <option value="created">Created</option>
                <option value="category">Category</option>
                <option value="status">Status</option>
            </select>
        </div>
    </div>

    <!-- Tasks Display -->
    <?php if (empty($tasks)): ?>
        <div class="empty">No tasks for today. Have a rest or start a tiny habit!</div>
    <?php else: ?>
        <!-- Uncategorized Tasks -->
        <?php if (!empty($uncategorizedTasks)): ?>
            <div class="task-section" data-category="Uncategorized">
                <div class="section-header">
                    <h3>Uncategorized</h3>
                    <span class="task-count"><?= count($uncategorizedTasks) ?></span>
                </div>
                <div class="task-list">
                    <?php foreach ($uncategorizedTasks as $task): ?>
                        <div class="task-item <?= ($task['status'] ?? '') === 'done' ? 'done' : '' ?>" data-task-id="<?= (int)$task['id'] ?>" data-status="<?= $task['status'] ?? 'todo' ?>">
                            <form method="post" action="?page=task_toggle" class="task-toggle-form" style="display: contents;">
                                <input type="hidden" name="csrf" value="<?= e(Core\Csrf::token()) ?>">
                                <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                                <button type="submit" class="task-checkbox">
                                    <?= ($task['status'] ?? '') === 'done' ? '✅' : '⬜' ?>
                                </button>
                            </form>
                            <div class="task-content">
                                <span class="task-title <?= $isPrivacyMode ? 'privacy-blur' : '' ?>">
                                    <?= e((string)$task['title']) ?>
                                </span>
                                <?php if ($task['due_at']): ?>
                                    <div class="task-meta">📅 <?= date('M j, Y', strtotime($task['due_at'])) ?></div>
                                <?php endif; ?>
                                <?php if ($task['reminder_at']): ?>
                                    <div class="task-meta">🔔 <?= date('M j H:i', strtotime($task['reminder_at'])) ?></div>
                                <?php endif; ?>
                            </div>
                            <form method="post" action="?page=task_delete" class="task-delete-form" style="display: contents;" onsubmit="return confirm('Delete this task?');">
                                <input type="hidden" name="csrf" value="<?= e(Core\Csrf::token()) ?>">
                                <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                                <button type="submit" class="task-delete" title="Delete task">✕</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Categorized Tasks -->
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
                        <div class="task-item <?= ($task['status'] ?? '') === 'done' ? 'done' : '' ?>" data-task-id="<?= (int)$task['id'] ?>" data-status="<?= $task['status'] ?? 'todo' ?>" data-category="<?= htmlspecialchars($categoryName) ?>">
                            <form method="post" action="?page=task_toggle" class="task-toggle-form" style="display: contents;">
                                <input type="hidden" name="csrf" value="<?= e(Core\Csrf::token()) ?>">
                                <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                                <button type="submit" class="task-checkbox">
                                    <?= ($task['status'] ?? '') === 'done' ? '✅' : '⬜' ?>
                                </button>
                            </form>
                            <div class="task-content">
                                <span class="task-title <?= $isPrivacyMode ? 'privacy-blur' : '' ?>">
                                    <?= e((string)$task['title']) ?>
                                </span>
                                <?php if ($task['due_at']): ?>
                                    <div class="task-meta">📅 <?= date('M j, Y', strtotime($task['due_at'])) ?></div>
                                <?php endif; ?>
                                <?php if ($task['reminder_at']): ?>
                                    <div class="task-meta">🔔 <?= date('M j H:i', strtotime($task['reminder_at'])) ?></div>
                                <?php endif; ?>
                            </div>
                            <form method="post" action="?page=task_delete" class="task-delete-form" style="display: contents;" onsubmit="return confirm('Delete this task?');">
                                <input type="hidden" name="csrf" value="<?= e(Core\Csrf::token()) ?>">
                                <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                                <button type="submit" class="task-delete" title="Delete task">✕</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    .task-stats {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: var(--surface-alt);
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        min-width: 100px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--primary);
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    .task-form {
        background: var(--surface-alt);
        padding: 1.5rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        margin-bottom: 2rem;
    }

    .task-controls {
        background: var(--surface-alt);
        padding: 1.5rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        margin-bottom: 2rem;
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .control-group {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .control-group label {
        font-weight: 600;
        color: var(--text);
        white-space: nowrap;
    }

    .category-filters {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .category-filter-btn {
        padding: 0.4rem 0.8rem;
        border: 2px solid;
        border-radius: 999px;
        background: transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .category-filter-btn:hover,
    .category-filter-btn.active {
        background: currentColor;
        color: white;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border);
    }

    .section-header h3 {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
        font-size: 1.1rem;
    }

    .category-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .task-count {
        background: var(--surface-alt);
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-muted);
    }

    .task-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .task-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--surface);
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        transition: all 0.2s ease;
    }

    .task-item:hover {
        background: var(--surface-alt);
        border-color: var(--primary);
    }

    .task-item.done {
        opacity: 0.6;
        background: rgba(0,0,0,0.02);
    }

    .task-checkbox {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        min-width: 24px;
        flex-shrink: 0;
    }

    .task-content {
        flex: 1;
        min-width: 0;
    }

    .task-title {
        display: block;
        font-weight: 600;
        word-break: break-word;
    }

    .task-item.done .task-title {
        text-decoration: line-through;
    }

    .task-meta {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .task-delete {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: var(--text-muted);
        padding: 0.25rem 0.5rem;
        flex-shrink: 0;
        transition: color 0.2s ease;
    }

    .task-delete:hover {
        color: var(--accent);
    }

    .privacy-blur {
        filter: blur(5px);
        opacity: 0.7;
        transition: filter 0.2s ease;
    }

    .privacy-blur:hover {
        filter: blur(0);
        opacity: 1;
    }

    @media (max-width: 768px) {
        .task-form-row {
            flex-direction: column;
        }

        .task-controls {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .category-filters {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category filter buttons
    const filterBtns = document.querySelectorAll('.category-filter-btn');
    const sections = document.querySelectorAll('.task-section');
    let activeFilter = null;
    let hideCompleted = false;

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const category = this.dataset.category;

            if (category === 'show-all') {
                activeFilter = null;
                hideCompleted = false;
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
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
            let show = true;
            const sectionCategory = section.dataset.category;

            if (activeFilter && sectionCategory !== activeFilter) {
                show = false;
            }

            section.style.display = show ? 'block' : 'none';

            // Hide completed tasks if needed
            if (show && hideCompleted) {
                const items = section.querySelectorAll('.task-item');
                items.forEach(item => {
                    item.style.display = item.classList.contains('done') ? 'none' : 'flex';
                });
            } else if (show) {
                const items = section.querySelectorAll('.task-item');
                items.forEach(item => {
                    item.style.display = 'flex';
                });
            }
        });
    }
});
</script>
