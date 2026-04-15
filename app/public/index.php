<?php
// app/public/index.php - The Front Controller and Router

// Include our core functions and action logic
require_once __DIR__ . '/../src/core.php';
require_once __DIR__ . '/../src/actions.php';

// --- DEBUGGING ---
// show errors on the screen
// In a real production app, you would turn these off for security.
ini_set('display_errors', '1');
error_reporting(E_ALL);

// --- SETUP ---
// Load database configuration from the .env.php file in the root
$config = require __DIR__ . '/../../.env.php';

// Connect to the database using our helper function in core.php
$pdo = db_connect($config['db']);

// Start the session (to keep track of logged-in users)
auth_start();

// --- ROUTING ---
// Get the page name from the URL
// If no page is specified, go to 'tasks' if logged in, otherwise 'login'.
$page = $_GET['page'] ?? (auth_user_id() ? 'tasks' : 'login');

// The switch statement decides which view to run based on the page name.
switch ($page) {
    
    // --- AUTHENTICATION ---
    case 'login': 
        render('login'); 
        break;
    case 'register': 
        render('register'); 
        break;
    case 'logout': 
        auth_logout(); 
        header('Location: ?page=login'); 
        break;
    case 'login_post': 
        action_login($pdo); 
        break;
    case 'register_post': 
        action_register($pdo); 
        break;

    // --- TASKS ---
    case 'tasks':
        auth_require(); // Must be logged in to see this
        render('tasks', [
            'tasks' => get_tasks($pdo, auth_user_id()),
            'categories' => get_categories($pdo)
        ]);
        break;
    case 'task_create': 
        auth_require(); 
        action_task_create($pdo, auth_user_id()); 
        break;
    case 'task_toggle': 
        auth_require(); 
        action_task_toggle($pdo, auth_user_id()); 
        break;
    case 'task_delete': 
        auth_require(); 
        action_task_delete($pdo, auth_user_id()); 
        break;

    // --- HABITS ---
    case 'habits':
        auth_require();
        render('habits', [
            'habits' => get_habits($pdo, auth_user_id())
        ]);
        break;
    case 'habit_create': 
        auth_require(); 
        action_habit_create($pdo, auth_user_id()); 
        break;
    case 'habit_check': 
        auth_require(); 
        action_habit_check($pdo, auth_user_id()); 
        break;

    // --- SETTINGS ---
    case 'settings':
        auth_require();
        $uid = auth_user_id();
        
        // Fetch user info
        $st = $pdo->prepare("SELECT id, display_name, email FROM users WHERE id = ?");
        $st->execute([$uid]);
        $user = $st->fetch();
        
        // Fetch user settings
        $st2 = $pdo->prepare("SELECT * FROM user_settings WHERE user_id = ?");
        $st2->execute([$uid]);
        $settings = $st2->fetch() ?: [];
        
        render('settings', ['user' => $user, 'settings' => $settings]);
        break;
    case 'settings_save': 
        auth_require(); 
        action_settings_save($pdo, auth_user_id()); 
        break;

    // --- SKILLS ---
    case 'skills':
        auth_require();
        render('skills', [
            'skills' => get_skills($pdo)
        ]);
        break;

    // --- CATEGORIES ---
    case 'categories':
        auth_require();
        render('categories', [
            'categories' => get_categories($pdo)
        ]);
        break;

    // --- PLANNER ---
    case 'planner':
        auth_require();
        $date = $_GET['date'] ?? date('Y-m-d');
        render('planner', [
            'tasks' => get_tasks($pdo, auth_user_id(), $date),
            'selectedDate' => $date
        ]);
        break;

    // --- CALENDAR ---
    case 'calendar':
        auth_require();
        render('calendar', [
            'tasks' => get_tasks($pdo, auth_user_id())
        ]);
        break;
    case 'calendar_export':
        auth_require();
        action_calendar_export($pdo, auth_user_id());
        break;

    // --- 404 NOT FOUND ---
    default:
        http_response_code(404);
        render('404');
}