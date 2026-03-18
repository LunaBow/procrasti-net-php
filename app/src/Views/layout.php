<?php
$title = $title ?? 'Procrasti-php';
$globalSettings = $globalSettings ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?></title>
    <!-- Use relative paths or dynamically echo the correct base path -->
    <link rel="stylesheet" href="../src/assets/css/global.css">
    
    <!-- Apply theme early to prevent flashbang -->
    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('pastel-mode');
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
    <header id="headerHeading">
        <div class="container">
            <h1><a href="?page=tasks" data-lang="header-title">procrasti-net</a></h1>
            <h2 data-lang="header-subtitle">Fuck it, we ball.</h2>
            <div id="controls">
                <div id="theme-switcher">
                    <label class="switch" aria-label="Toggle theme">
                        <input type="checkbox" id="theme-switcher-checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div id="language-switcher">
                    <select id="language-select">
                        <option value="en">EN</option>
                        <option value="de">DE</option>
                    </select>
                </div>
            </div>
        </div>
    </header>

    <section id="nav_ul">
        <div id="navWrapper" class="nav-stack">
            <!-- Row 1: core app navigation -->
            <nav class="nav-core" aria-label="Primary navigation">
                <div class="dropdown-wrapper"><a id="triggerTodos" href="?page=tasks" data-lang="nav-todos">To-dos</a></div>
                <div class="dropdown-wrapper"><a id="triggerSkills" href="?page=skills" data-lang="nav-skills">Skills</a></div>
                <div class="dropdown-wrapper"><a id="triggerHabits" href="?page=habits" data-lang="nav-habits">Habits</a></div>
                <div class="dropdown-wrapper"><a id="triggerPlanner" href="?page=planner" data-lang="nav-planner">Planner</a></div>
                <div class="dropdown-wrapper"><a id="triggerCalendar" href="?page=calendar" data-lang="nav-calendar">Calendar</a></div>
                <div class="dropdown-wrapper"><a id="triggerSettings" href="?page=settings" data-lang="nav-settings">Settings</a></div>
                <?php if (Core\Auth::userId()): ?>
                    <div class="dropdown-wrapper"><a href="?page=logout" style="color: var(--accent);" data-lang="nav-logout">Logout</a></div>
                <?php else: ?>
                    <div class="dropdown-wrapper"><a href="?page=login" style="color: var(--primary);" data-lang="nav-login">Login</a></div>
                <?php endif; ?>
            </nav>
        </div>

        <!-- Extra Navigation Slide-out Menu -->
        <nav class="nav-extra-menu" aria-label="Extra navigation">
            <div class="dropdown-wrapper">
                <a id="triggerEvaluation" class="dropbtn" href="https://www.mindtools.com/pages/main/newMN_80.htm" data-lang="nav-mindtools-reality-check">MindTools Reality Check</a>
                <div class="dropdown-content">
                    <a id="triggerRequired" href="https://www.productivityist.com/bare-minimum-tasks/" data-lang="nav-productivityist-bare-minimum">Productivityist Bare Minimum</a>
                    <a id="triggerBoot" href="https://www.headspace.com/" data-lang="nav-headspace-mindfulness">Headspace Mindfulness</a>
                </div>
            </div>
            <div class="dropdown-wrapper">
                <a id="triggerArt" class="dropbtn" href="https://www.newgrounds.com/" data-lang="nav-newgrounds-games">Newgrounds Games</a>
                <div class="dropdown-content">
                    <a id="showDrawingsOnly" href="https://www.deviantart.com/" data-lang="nav-deviantart-gallery">DeviantArt Gallery</a>
                    <a id="showAVOnly" href="https://www.youtube.com/" data-lang="nav-youtube-videos">YouTube Videos</a>
                    <a id="showBooksOnly" href="https://www.gutenberg.org/" data-lang="nav-gutenberg-books">Project Gutenberg Books</a>
                </div>
            </div>
            <div class="dropdown-wrapper">
                <a id="TriggerMember" class="dropbtn" href="https://www.reddit.com/" data-lang="nav-reddit-community">Reddit Community</a>
            </div>
        </nav>
    </section>

    <div id="JoinContent" style="display: block;">
        <main>
            <?php if ($msg = flash('error')): ?>
                <div class="container" style="text-align: center; margin-bottom: 1rem;">
                    <p class="error" style="color: var(--accent); font-weight: bold;"><?= htmlspecialchars($msg) ?></p>
                </div>
            <?php endif; ?>
            
            <?php include $content; ?>
        </main>
    </div>

    <section id="footer">
        <footer>
            <div class="footer-site-footercontainer">
                <div class="footer-about">
                    <p><strong data-lang="footer-about-title">Procrasti-net</strong></p>
                    <p data-lang="footer-about-p1">We're here to do tasks and chew bubblegum. And we have a lot of bubble gum.</p>
                    <p data-lang="footer-about-p2">Build for Luna and Verena, may they stop procrastinating and start working on shit.</p>
                </div>
                <div class="footer-contact">
                    <p><strong data-lang="footer-contact-title">Contact</strong></p>
                    <p data-lang="footer-contact-p1">Email:</p>
                    <p data-lang="footer-contact-p2">mt231043@ustp-students.at,</p>
                    <p data-lang="footer-contact-p3">mt241068@ustp-students.at,</p>
                    <p data-lang="footer-contact-p4">asteudres@ustp.at</p>
                    <p data-lang="footer-contact-p5">Phone: +43676/7875431</p>
                    <p data-lang="footer-contact-p6">We'll reply eventually. It's on the todo list. Maybe Tomorrow.</p>
                </div>
            </div>
        </footer>
    </section>
</div>

<script src="../src/assets/js/languages.js"></script>
<script src="../src/assets/js/app.js" defer></script>
</body>
</html>