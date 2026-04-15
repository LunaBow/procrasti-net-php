<?php 
// app/src/Views/settings.php - User profile and app preferences (change in javascript file :)
$title = 'Settings'; 
?>

<div class="content panel" style="margin: 0 auto; max-width: 600px;">
    <h2 style="font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: -1px; color: var(--primary); margin-top: 0; margin-bottom: 2rem; text-align: center;">Settings</h2>

    <!-- Show a success message if settings were just saved -->
    <?php if ($msg = flash('success')): ?>
        <div style="background: rgba(46, 213, 115, 0.15); color: #2ed573; padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 2rem; font-weight: bold; border: 1px solid rgba(46, 213, 115, 0.3);">
            <?= e($msg) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="?page=settings_save" class="stack">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        
        <h3 style="color: var(--text); border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem; margin-bottom: 1rem;">Profile</h3>
        
        <div class="field" style="text-align: left; margin-bottom: 1.5rem;">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Display Name</label>
            <input name="display_name" type="text" value="<?= e($user['display_name'] ?? '') ?>" class="item" style="width: 100%; box-sizing: border-box;" required>
            <p class="muted" style="font-size: 0.8rem; margin-top: 0.5rem;">This is what the app will call you.</p>
        </div>

        <div class="field" style="text-align: left; margin-bottom: 2rem;">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Email</label>
            <input type="text" value="<?= e($user['email'] ?? '') ?>" class="item" style="width: 100%; box-sizing: border-box; opacity: 0.6; cursor: not-allowed;" disabled>
            <p class="muted" style="font-size: 0.8rem; margin-top: 0.5rem;">Email cannot be changed yet.</p>
        </div>

        <h3 style="color: var(--text); border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem; margin-bottom: 1rem;">App Features</h3>


        <div class="field check" style="margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 1rem;">
            <input type="checkbox" name="allow_gamification" id="allow_gamification" <?= ($settings['allow_gamification'] ?? 1) ? 'checked' : '' ?> style="margin-top: 0.3rem;">
            <label for="allow_gamification" style="cursor: pointer;">
                <strong style="display: block;">Enable Gamification</strong>
                <span class="muted" style="font-size: 0.85rem; font-weight: normal;">Adds some "fun" elements to task management.</span>
            </label>
        </div>

        <div class="field check" style="margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 1rem;">
            <input type="checkbox" name="privacy_mode" id="privacy_mode" <?= ($settings['privacy_mode'] ?? 0) ? 'checked' : '' ?> style="margin-top: 0.3rem;">
            <label for="privacy_mode" style="cursor: pointer;">
                <strong style="display: block;">Privacy Mode</strong>
                <span class="muted" style="font-size: 0.85rem; font-weight: normal;">Blurs task titles until you hover over them.</span>
            </label>
        </div>
        
        <div class="field check" style="margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 1rem;">
            <input type="checkbox" name="sarcastic_comments" id="sarcastic_comments" <?= ($settings['sarcastic_comments'] ?? 0) ? 'checked' : '' ?> style="margin-top: 0.3rem;">
            <label for="sarcastic_comments" style="cursor: pointer;">
                <strong style="display: block;">Sarcastic Comments</strong>
                <span class="muted" style="font-size: 0.85rem; font-weight: normal;">Enables little "insults" if you have overdue tasks.</span>
            </label>
        </div>
        
        <div class="field check" style="margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 1rem;">
            <input type="checkbox" name="hand_drawn_mode" id="hand_drawn_mode" <?= ($settings['hand_drawn_mode'] ?? 0) ? 'checked' : '' ?> style="margin-top: 0.3rem;">
            <label for="hand_drawn_mode" style="cursor: pointer;">
                <strong style="display: block;">Hand-Drawn Mode</strong>
                <span class="muted" style="font-size: 0.85rem; font-weight: normal;">Makes everything look like a rough sketch.</span>
            </label>
        </div>
        
        <div class="field check" style="margin-bottom: 2rem; display: flex; align-items: flex-start; gap: 1rem;">
            <input type="checkbox" name="leet_speak" id="leet_speak" <?= ($settings['leet_speak'] ?? 0) ? 'checked' : '' ?> style="margin-top: 0.3rem;">
            <label for="leet_speak" style="cursor: pointer;">
                <strong style="display: block;">1337 5P34K</strong>
                <span class="muted" style="font-size: 0.85rem; font-weight: normal;">7URN5 411 73X7 1N70 1337 5P34K.</span>
            </label>
        </div>
        
        <button type="submit" class="pill" style="width: 100%; padding: 1rem;">Save Settings</button>
    </form>
</div>
