<?php
// app/src/Views/layout.php - The main wrapper for all pages
$title = $title ?? 'Procrastinate-php';
$globalSettings = $globalSettings ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?></title>
    
    <!-- Link to our global CSS styles -->
    <link rel="stylesheet" href="../src/assets/css/global.css">
    
    <!-- AI Help: This small script runs before the page loads to prevent a "flash" of white if dark mode is on. -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme) {
                if (theme === 'dark') {
                    document.documentElement.classList.add('pastel-mode');
                } else if (theme !== 'light') {
                    document.documentElement.classList.add(theme);
                }
            }
        })();
    </script>
</head>
<body 
    data-hand-drawn-mode="<?= !empty($globalSettings['hand_drawn_mode']) ? 'true' : 'false' ?>"
    data-leet-speak="<?= !empty($globalSettings['leet_speak']) ? 'true' : 'false' ?>"
    data-sarcastic-comments="<?= !empty($globalSettings['sarcastic_comments']) ? 'true' : 'false' ?>"
>

<div id="entireContentWrapper">
    <!-- Header with Title and Theme/Language Switchers -->
    <header id="headerHeading">
        <div class="container">
            <h1><a href="?page=tasks" data-lang="header-title">procrastinate-net</a></h1>
            <h2 data-lang="header-subtitle">Fuck it, we ball.</h2>
            <div id="controls">
                <!-- Theme Selection -->
                <div id="theme-switcher">
                    <select id="theme-select" aria-label="Select theme" style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background: var(--surface); border: 1px solid var(--border); border-radius: 999px; padding: 0.5rem 1rem; font-weight: 600; color: var(--text); cursor: pointer; transition: all 0.2s ease;">
                        <option value="light">Light</option>
                        <option value="dark">Dark</option>
                        <option value="blue-theme">Blue</option>
                        <option value="green-theme">Green</option>
                        <option value="purple-theme">Purple</option>
                        <option value="pink-theme">Pink</option>
                        <option value="orange-theme">Orange</option>
                    </select>
                </div>
                <!-- Language Selection -->
                <div id="language-switcher">
                    <select id="language-select">
                        <option value="en">EN</option>
                        <option value="de">DE</option>
                    </select>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation Bar -->
    <section id="nav_ul">
        <div id="navWrapper" class="nav-stack">
            <nav class="nav-core" aria-label="Primary navigation">
                <div class="dropdown-wrapper"><a id="triggerTodos" href="?page=tasks" data-lang="nav-todos">To-dos</a></div>
                <div class="dropdown-wrapper"><a id="triggerSkills" href="?page=skills" data-lang="nav-skills">Skills</a></div>
                <div class="dropdown-wrapper"><a id="triggerHabits" href="?page=habits" data-lang="nav-habits">Habits</a></div>
                <div class="dropdown-wrapper"><a id="triggerPlanner" href="?page=planner" data-lang="nav-planner">Planner</a></div>
                <div class="dropdown-wrapper"><a id="triggerCalendar" href="?page=calendar" data-lang="nav-calendar">Calendar</a></div>
                <div class="dropdown-wrapper"><a id="triggerSettings" href="?page=settings" data-lang="nav-settings">Settings</a></div>
                
                <?php if (auth_user_id()): ?>
                    <div class="dropdown-wrapper"><a href="?page=logout" style="color: var(--accent);" data-lang="nav-logout">Logout</a></div>
                <?php else: ?>
                    <div class="dropdown-wrapper"><a href="?page=login" style="color: var(--primary);" data-lang="nav-login">Login</a></div>
                <?php endif; ?>
            </nav>
        </div>

        <!-- Extra Links (Connections, Distractions, Resources) -->
        <nav class="nav-extra-menu" aria-label="Extra navigation">
            <div class="dropdown-wrapper">
                <a id="triggerConnections" class="dropbtn" href="#" data-lang="nav-my-connections">My Connections</a>
                <div class="dropdown-content">
                    <a href="https://www.youtube.com/@LunarBower" data-lang="nav-youtube-lunarbower">YouTube @LunarBower</a>
                    <a href="https://github.com/LunaBow" data-lang="nav-github-lunabow">GitHub LunaBow</a>
                    <a href="https://open.spotify.com/artist/2rWHud9CFPTzoDgCKh0t4S" data-lang="nav-spotify-lunarbower">Spotify Lunar Bower</a>
                </div>
            </div>
            <div class="dropdown-wrapper">
                <a id="triggerDistractions" class="dropbtn" href="#" data-lang="nav-distractions">Distractions</a>
                <div class="dropdown-content">
                    <a href="https://www.newgrounds.com/" data-lang="nav-newgrounds-games">Newgrounds Games</a>
                    <a href="https://www.reddit.com/" data-lang="nav-reddit-community">Reddit Community</a>
                </div>
            </div>
            <div class="dropdown-wrapper">
                <a id="triggerResources" class="dropbtn" href="#" data-lang="nav-resources">Resources</a>
                <div class="dropdown-content">
                    <a href="https://www.mindtools.com/pages/main/newMN_80.htm" data-lang="nav-mindtools-reality-check">MindTools Reality Check</a>
                    <a href="https://www.headspace.com/" data-lang="nav-headspace-mindfulness">Headspace Mindfulness</a>
                </div>
            </div>
        </nav>
    </section>

    <!-- Main Content Area -->
    <div id="JoinContent" style="display: block;">
        <main>
            <!-- Show error messages if they exist in the flash session -->
            <?php if ($msg = flash('error')): ?>
                <div class="container" style="text-align: center; margin-bottom: 1rem;">
                    <p class="error" style="color: var(--accent); font-weight: bold;"><?= htmlspecialchars($msg) ?></p>
                </div>
            <?php endif; ?>
            
            <!-- AI Help: include $content actually puts the code from the view file here. -->
            <?php if (isset($content) && file_exists($content)): ?>
                <?php include $content; ?>
            <?php else: ?>
                <p>Content not available.</p>
            <?php endif; ?>
        </main>
    </div>

    <!-- Footer Area -->
    <section id="footer">
        <footer>
            <div class="footer-site-footercontainer">
                <div class="footer-about">
                    <p><strong data-lang="footer-about-title">Procrastinate-net</strong></p>
                    <p data-lang="footer-about-p1">We're here to do tasks and chew bubblegum. And we have a lot of bubble gum.</p>
                </div>
                <div class="footer-contact">
                    <p><strong data-lang="footer-contact-title">Contact</strong></p>
                    <p data-lang="footer-contact-p2">mt231043@ustp-students.at</p>
                    <p data-lang="footer-contact-p6">We'll reply eventually. It's on the todo list. Maybe Tomorrow.</p>
                </div>
            </div>
        </footer>
    </section>
</div>

<!-- Load JavaScript at the end for better performance -->
<script src="../src/assets/js/languages.js"></script>
<script src="../src/assets/js/app.js" defer></script>
</body>
</html>