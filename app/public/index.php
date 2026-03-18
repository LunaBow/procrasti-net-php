<?php
declare(strict_types=1);

// custom autoloader
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../src/';
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use Core\Database;
use Core\Auth;
use Controllers\AuthController;
use Controllers\TaskController;
use Controllers\CategoryController;
use Controllers\SkillController;
use Controllers\HabitController;
use Controllers\PlannerController;
use Controllers\CalendarController;
use Controllers\SettingsController;
use Repos\TaskRepo;
use Repos\CategoryRepo;
use Repos\SkillRepo;
use Repos\HabitRepo;
use Repos\SettingsRepo;

// Pulling in my DB secrets from outside the public folder (security first!)
$config = require __DIR__ . '/../../.env.php';

$db = new Database($config['db']);
$pdo = $db->pdo();

// Just making sure the habit tables exist if they don't already. 
try {
    $setupSql = file_get_contents(__DIR__ . '/../src/db/habits_setup.sql');
    if ($setupSql) {
        $pdo->exec($setupSql);
    }
} catch (\Throwable $e) {}

try {
    // Run the main settings table setup
    $settingsSql = file_get_contents(__DIR__ . '/../src/db/settings_setup.sql');
    if ($settingsSql) {
        $pdo->exec($settingsSql);
    }
    
    // Run the migration for new columns separately so it doesn't break the initial setup
    $migrationSql = file_get_contents(__DIR__ . '/../src/db/settings_migration.sql');
    if ($migrationSql) {
        // Split by semicolon to execute queries individually, 
        // ignoring errors if columns already exist.
        $queries = array_filter(array_map('trim', explode(';', $migrationSql)));
        foreach ($queries as $query) {
            try {
                $pdo->exec($query);
            } catch (\PDOException $e) {
                // Ignore "Duplicate column name" errors
            }
        }
    }
} catch (\Throwable $e) {}

// start session so we know who's logged in.
Auth::start();

// Quick helper to escape HTML output so nobody can XSS me.
function e(string $s): string { 
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); 
}

// Flash messages for little "hey you fucked up your password" popups.
function flash(string $key, ?string $set = null): ?string {
    Auth::start();
    if ($set !== null) { 
        $_SESSION['flash'][$key] = $set; 
        return null; 
    }
    $val = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $val;
}

// Wiring up the repos and controllers. 
$taskRepo = new TaskRepo($pdo);
$settingsRepo = new SettingsRepo($pdo);

$auth = new AuthController($pdo);
$tasks = new TaskController($taskRepo, $settingsRepo);
$categories = new CategoryController(new CategoryRepo($pdo));
$skills = new SkillController(new SkillRepo($pdo));
$habits = new HabitController(new HabitRepo($pdo));
$planner = new PlannerController($taskRepo);
$calendar = new CalendarController($taskRepo);
$settings = new SettingsController($settingsRepo);

// Simple layout renderer.
function render(string $view, array $data = []): void {
    global $settingsRepo;
    
    // Fetch global settings for the layout
    $globalSettings = [];
    if (Auth::userId()) {
        $globalSettings = $settingsRepo->getSettings(Auth::userId());
    }
    
    extract($data);
    $content = __DIR__ . '/../src/Views/' . $view . '.php';
    require __DIR__ . '/../src/Views/layout.php';
}

// Main router. If no page is set, dump them on tasks or login.
$page = $_GET['page'] ?? (Auth::userId() ? 'tasks' : 'login');

switch ($page) {
    case 'login': $auth->showLogin(); break;
    case 'register': $auth->showRegister(); break;
    case 'login_post': $auth->login(); break;
    case 'register_post': $auth->register(); break;
    case 'logout': $auth->logout(); break;

    case 'tasks': $tasks->index(); break;
    case 'task_create': $tasks->create(); break;
    case 'task_toggle': $tasks->toggle(); break;

    case 'categories': $categories->index(); break;
    case 'skills': $skills->index(); break;

    case 'habits': $habits->index(); break;
    case 'habit_create': $habits->create(); break;
    case 'habit_check': $habits->check(); break;
    
    case 'planner': $planner->index(); break;
    
    case 'calendar': $calendar->index(); break;
    case 'calendar_export': $calendar->export(); break;
    
    case 'settings': $settings->index(); break;
    case 'settings_save': $settings->save(); break;

    default:
        http_response_code(404);
        render('404');
}