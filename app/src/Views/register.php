<?php 
// app/src/Views/register.php - User registration page
$title = 'Register'; 
?>

<div class="content" style="max-width: 400px; margin: 4rem auto; text-align: center;">
    <h1 style="margin-bottom: 2rem;">Create Account</h1>

    <form method="post" action="?page=register_post" class="stack">
        <!-- Security Token -->
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        
        <div class="field" style="text-align: left;">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Display Name</label>
            <input name="display_name" type="text" class="item" style="width: 100%; box-sizing: border-box;" required placeholder="What should we call you?">
        </div>

        <div class="field" style="text-align: left;">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Email</label>
            <input name="email" type="email" class="item" style="width: 100%; box-sizing: border-box;" required placeholder="Your email address">
        </div>
        
        <div class="field" style="text-align: left; margin-bottom: 1.5rem;">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Password</label>
            <input name="password" type="password" class="item" style="width: 100%; box-sizing: border-box;" required placeholder="Create a strong password">
        </div>
        
        <button type="submit" class="pill" style="width: 100%;">Create account</button>
    </form>

    <p style="margin-top: 2rem; color: var(--text-muted);">
        Already have an account? <a href="?page=login" style="font-weight: 700;">Back to login</a>
    </p>
</div>