<?php $title = 'Login - Procrasti-php'; ?>

<div class="content" style="max-width: 400px; margin: 4rem auto; text-align: center;">
    <h1 style="margin-bottom: 2rem;">Welcome Back</h1>

    <form method="post" action="?page=login_post" class="stack">
        <input type="hidden" name="csrf" value="<?= e(Core\Csrf::token()) ?>">
        
        <div class="field" style="text-align: left;">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Email</label>
            <input name="email" type="email" class="item" style="width: 100%; box-sizing: border-box;" required>
        </div>
        
        <div class="field" style="text-align: left; margin-bottom: 1.5rem;">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Password</label>
            <input name="password" type="password" class="item" style="width: 100%; box-sizing: border-box;" required>
        </div>
        
        <button type="submit" class="pill" style="width: 100%;">Login</button>
    </form>

    <p style="margin-top: 2rem; color: var(--text-muted);">
        Don't have an account? <a href="?page=register" style="font-weight: 700;">Register here</a>
    </p>
</div>