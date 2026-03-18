<?php 
// If they have privacy mode on, we'll blur the text until they hover over it
$isPrivacyMode = ($settings['privacy_mode'] ?? 0) == 1;
$title = 'My Tasks'; 
?>

<div class="content">
    <h1>Today's Tasks</h1>
    <p class="muted" style="margin-bottom: 2rem;">What's the next stupid easy step?</p>

    <form class="controls" method="post" action="?page=task_create">
        <input type="hidden" name="csrf" value="<?= e(Core\Csrf::token()) ?>">
        <div class="field" style="flex: 1;">
            <input type="text" name="title" class="item" placeholder="e.g. Open the document..." required style="width: 100%;">
        </div>
        <button type="submit" class="pill">Add Task</button>
    </form>

    <?php if (empty($tasks)): ?>
        <div class="empty">No tasks for today. Have a rest or start a tiny habit!</div>
    <?php else: ?>
        <ul class="todo-list" style="list-style: none; padding: 0;">
            <?php foreach ($tasks as $t): ?>
                <li class="todo-item <?= (($t['status'] ?? '') === 'done') ? 'done' : '' ?>" data-id="<?= (int)$t['id'] ?>">
                    <button class="toggleBtn" type="button" data-csrf="<?= e(Core\Csrf::token()) ?>" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">
                        <?= (($t['status'] ?? '') === 'done') ? '✅' : '⬜' ?>
                    </button>
                    <span class="title <?= $isPrivacyMode ? 'privacy-blur' : '' ?>" style="transition: filter 0.2s ease;">
                        <?= e((string)$t['title']) ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
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
</style>