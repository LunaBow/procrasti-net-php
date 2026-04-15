<?php
// app/src/actions.php - Logic for different pages and database operations

// --- USER AUTHENTICATION ---

/**
 * Handle user registration
 */
function action_register($pdo) {
    csrf_verify($_POST['csrf'] ?? null); // Security check
    
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['display_name'] ?? '');
    $pass = $_POST['password'] ?? '';

    // Basic validation
    if ($email === '' || $name === '' || $pass === '') {
        flash('error', 'Please fill in all fields.');
        header('Location: ?page=register');
        exit;
    }

    // password_hash creates a secure, encrypted version of the password.
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    
    try {
        $st = $pdo->prepare("INSERT INTO users (email, display_name, password_hash) VALUES (?, ?, ?)");
        $st->execute([$email, $name, $hash]);
        
        // Log the user in immediately after registering
        auth_login($pdo->lastInsertId());
        header('Location: ?page=tasks');
    } catch (PDOException $e) {
        // fail if email is already in use
        flash('error', 'Registration failed. Maybe that email is already used?');
        header('Location: ?page=register');
    }
    exit;
}

/**
 * Handle user login
 */
function action_login($pdo) {
    csrf_verify($_POST['csrf'] ?? null);
    
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    $st = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
    $st->execute([$email]);
    $u = $st->fetch();

    // password_verify checks if the plain text password matches the hashed one in the DB.
    if (!$u || !password_verify($pass, $u['password_hash'])) {
        flash('error', 'Wrong email or password.');
        header('Location: ?page=login');
        exit;
    }

    auth_login($u['id']);
    header('Location: ?page=tasks');
    exit;
}

// --- TASKS ---

/**
 * Fetch tasks for a specific user, optionally for a specific date
 */
function get_tasks($pdo, $uid, $date = null) {
    if ($date) {
        // AI Help: COALESCE(t.due_at, t.created_at) uses due_at if it exists, otherwise it falls back to created_at.
        $st = $pdo->prepare("SELECT t.*, c.name as category_name, c.color_code FROM tasks t LEFT JOIN categories c ON t.category_id = c.id WHERE t.user_id = ? AND DATE(COALESCE(t.due_at, t.created_at)) = ? ORDER BY t.id DESC");
        $st->execute([$uid, $date]);
    } else {
        $st = $pdo->prepare("SELECT t.*, c.name as category_name, c.color_code FROM tasks t LEFT JOIN categories c ON t.category_id = c.id WHERE t.user_id = ? ORDER BY t.id DESC");
        $st->execute([$uid]);
    }
    return $st->fetchAll();
}

/**
 * Export tasks to an iCalendar (.ics) file
 */
function action_calendar_export($pdo, $uid) {
    $from = $_GET['from'] ?? date('Y-m-d');
    $to = $_GET['to'] ?? date('Y-m-d');
    
    $st = $pdo->prepare("SELECT title, due_at, created_at FROM tasks WHERE user_id = ? AND DATE(COALESCE(due_at, created_at)) BETWEEN ? AND ?");
    $st->execute([$uid, $from, $to]);
    $tasks = $st->fetchAll();
    
    // AI Help: Sending these headers tells the browser that this is a downloadable file, not a webpage.
    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="procrasti-export.ics"');
    
    echo "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Procrasti-php//EN\r\n";
    foreach ($tasks as $t) {
        $date = date('Ymd\THis', strtotime($t['due_at'] ?? $t['created_at']));
        echo "BEGIN:VEVENT\r\n";
        echo "SUMMARY:" . str_replace(',', '\,', $t['title']) . "\r\n";
        echo "DTSTART:$date\r\n";
        echo "DTEND:$date\r\n";
        echo "END:VEVENT\r\n";
    }
    echo "END:VCALENDAR";
    exit;
}

/**
 * Create a new task
 */
function action_task_create($pdo, $uid) {
    csrf_verify($_POST['csrf'] ?? null);
    
    $title = $_POST['title'] ?? '';
    $catId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $due = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $remind = !empty($_POST['reminder_at']) ? $_POST['reminder_at'] : null;

    if ($title) {
        $st = $pdo->prepare("INSERT INTO tasks (user_id, title, category_id, due_at, reminder_at) VALUES (?, ?, ?, ?, ?)");
        $st->execute([$uid, $title, $catId, $due, $remind]);
    }
    header('Location: ?page=tasks');
    exit;
}

/**
 * Toggle a task between 'todo' and 'done'
 */
function action_task_toggle($pdo, $uid) {
    csrf_verify($_POST['csrf'] ?? null);
    $id = (int)($_POST['id'] ?? 0);
    
    $st = $pdo->prepare("SELECT status FROM tasks WHERE id = ? AND user_id = ?");
    $st->execute([$id, $uid]);
    $row = $st->fetch();
    
    if ($row) {
        $newStatus = ($row['status'] === 'done') ? 'todo' : 'done';
        // AI Help: We use a CASE statement in SQL to set completed_at only if the task was just marked 'done'.
        $up = $pdo->prepare("UPDATE tasks SET status = ?, completed_at = CASE WHEN ? = 'done' THEN NOW() ELSE NULL END WHERE id = ? AND user_id = ?");
        $up->execute([$newStatus, $newStatus, $id, $uid]);
        
        // If it was an AJAX (JavaScript) request, return JSON instead of redirecting
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['id' => $id, 'status' => $newStatus]);
            exit;
        }
    }
    header('Location: ?page=tasks');
    exit;
}

/**
 * Delete a task
 */
function action_task_delete($pdo, $uid) {
    csrf_verify($_POST['csrf'] ?? null);
    $id = (int)($_POST['id'] ?? 0);
    
    $st = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $st->execute([$id, $uid]);
    
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
    header('Location: ?page=tasks');
    exit;
}

// --- HABITS ---

/**
 * Fetch habits and their completion counts for a specific user
 */
function get_habits($pdo, $uid) {
    $st = $pdo->prepare("SELECT * FROM habits WHERE user_id = ?");
    $st->execute([$uid]);
    $habits = $st->fetchAll();
    
    // Minimal "streak" calculation: just count total completions for now
    foreach ($habits as &$h) {
        $st2 = $pdo->prepare("SELECT COUNT(*) FROM habit_logs WHERE habit_id = ?");
        $st2->execute([$h['id']]);
        $h['streak'] = $st2->fetchColumn();
    }
    return $habits;
}

/**
 * Create a new habit
 */
function action_habit_create($pdo, $uid) {
    csrf_verify($_POST['csrf'] ?? null);
    $name = $_POST['name'] ?? '';
    
    if ($name) {
        $st = $pdo->prepare("INSERT INTO habits (user_id, name) VALUES (?, ?)");
        $st->execute([$uid, $name]);
    }
    header('Location: ?page=habits');
    exit;
}

/**
 * Mark a habit as done for today
 */
function action_habit_check($pdo, $uid) {
    csrf_verify($_POST['csrf'] ?? null);
    $id = (int)($_POST['id'] ?? 0);
    $date = $_POST['date'] ?? date('Y-m-d');
    
    // Ensure the habit actually belongs to this user
    $st = $pdo->prepare("SELECT id FROM habits WHERE id = ? AND user_id = ?");
    $st->execute([$id, $uid]);
    
    if ($st->fetch()) {
        // AI Help: INSERT IGNORE skips the operation if a record already exists for this habit and date.
        $st2 = $pdo->prepare("INSERT IGNORE INTO habit_logs (habit_id, done_date) VALUES (?, ?)");
        $st2->execute([$id, $date]);
        
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
    }
    header('Location: ?page=habits');
    exit;
}

// --- SETTINGS ---

/**
 * Save user profile settings and display options
 */
function action_settings_save($pdo, $uid) {
    csrf_verify($_POST['csrf'] ?? null);
    
    // Update display name in 'users' table
    $name = $_POST['display_name'] ?? '';
    if ($name) {
        $st = $pdo->prepare("UPDATE users SET display_name = ? WHERE id = ?");
        $st->execute([$name, $uid]);
    }
    
    // Collect feature settings from checkboxes
    $gamification = isset($_POST['allow_gamification']) ? 1 : 0;
    $privacy = isset($_POST['privacy_mode']) ? 1 : 0;
    $sarcasm = isset($_POST['sarcastic_comments']) ? 1 : 0;
    $handDrawn = isset($_POST['hand_drawn_mode']) ? 1 : 0;
    $leet = isset($_POST['leet_speak']) ? 1 : 0;
    
    // AI Help: ON DUPLICATE KEY UPDATE either inserts a new row or updates the existing one if user_id matches.
    $st = $pdo->prepare("INSERT INTO user_settings (user_id, allow_gamification, privacy_mode, sarcastic_comments, hand_drawn_mode, leet_speak) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE allow_gamification=VALUES(allow_gamification), privacy_mode=VALUES(privacy_mode), sarcastic_comments=VALUES(sarcastic_comments), hand_drawn_mode=VALUES(hand_drawn_mode), leet_speak=VALUES(leet_speak)");
    $st->execute([$uid, $gamification, $privacy, $sarcasm, $handDrawn, $leet]);
    
    flash('success', 'Settings saved!');
    header('Location: ?page=settings');
    exit;
}

// --- SKILLS ---

/**
 * Fetch all skills from the database
 */
function get_skills($pdo) {
    $st = $pdo->prepare("SELECT s.*, c.name as category_name FROM skills s JOIN categories c ON s.category_id = c.id");
    $st->execute();
    return $st->fetchAll();
}

// --- CATEGORIES ---

/**
 * Fetch all task categories
 */
function get_categories($pdo) {
    $st = $pdo->query("SELECT * FROM categories");
    return $st->fetchAll();
}
