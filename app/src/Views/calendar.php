<?php 
$title = 'Calendar Export - Procrasti-php'; 

// Generate Calendar logic
$now = new DateTime();
$currentMonth = (int)$now->format('n');
$currentYear = (int)$now->format('Y');

$firstDayOfMonth = new DateTime("$currentYear-$currentMonth-01");
$daysInMonth = (int)$firstDayOfMonth->format('t');
// 0 (Sunday) to 6 (Saturday)
$firstDayOfWeek = (int)$firstDayOfMonth->format('w');

$days = array_fill(0, $firstDayOfWeek, null);

for ($i = 1; $i <= $daysInMonth; $i++) {
    $days[] = [
        'num' => $i,
        'isToday' => $i === (int)$now->format('j')
    ];
}
?>

<div class="content panel" style="margin: 0 1.5rem; max-width: none;">
    <h2 style="font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: -1px; color: var(--primary); margin-top: 0; margin-bottom: 2rem;">Calendar Export</h2>

    <form class="export-box" method="get" action="index.php" style="display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: flex-end; padding: 1.5rem; background: rgba(0, 0, 0, 0.2); border: 1px dashed var(--border); border-radius: var(--radius-md); margin-bottom: 2rem;">
        <input type="hidden" name="page" value="calendar_export">
        
        <label style="display: flex; flex-direction: column; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); gap: 0.5rem;">
            From 
            <input type="date" name="from" value="<?= date('Y-m-d') ?>" style="background: var(--bg); border: 1px solid var(--border); color: var(--text); padding: 0.6rem; border-radius: var(--radius-sm); font-family: 'JetBrains Mono', monospace;">
        </label>
        
        <label style="display: flex; flex-direction: column; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); gap: 0.5rem;">
            To 
            <input type="date" name="to" value="<?= date('Y-m-d') ?>" style="background: var(--bg); border: 1px solid var(--border); color: var(--text); padding: 0.6rem; border-radius: var(--radius-sm); font-family: 'JetBrains Mono', monospace;">
        </label>

        <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); font-weight: 800; text-transform: uppercase; font-size: 0.8rem; cursor: pointer;">
            Download .ics
        </button>
    </form>

    <div class="calendar-container">
        <h3 style="font-family: 'JetBrains Mono', monospace; color: var(--text); text-transform: uppercase; margin-bottom: 1rem; font-size: 1.2rem;">
            <?= $now->format('F Y') ?>
        </h3>
        <div class="calendar-grid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: var(--border); border: 1px solid var(--border);">
            <?php foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $d): ?>
                <div class="day-name" style="background: rgba(0, 0, 0, 0.4); padding: 0.5rem; text-align: center; font-size: 0.7rem; font-weight: 900; color: var(--primary); text-transform: uppercase;">
                    <?= $d ?>
                </div>
            <?php endforeach; ?>

            <?php foreach ($days as $day): ?>
                <div class="day" style="background: <?= $day && $day['isToday'] ? 'rgba(255, 0, 85, 0.05)' : 'var(--surface)' ?>; min-height: 80px; padding: 0.5rem; position: relative; <?= $day && $day['isToday'] ? 'border-top: 2px solid var(--primary);' : '' ?>">
                    <?php if ($day): ?>
                        <span class="date-num" style="font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: var(--text-muted);"><?= $day['num'] ?></span>
                        <?php if ($day['isToday']): ?>
                            <div class="event-indicator" title="Today" style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%; margin-top: 4px; box-shadow: 0 0 5px var(--primary);"></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
