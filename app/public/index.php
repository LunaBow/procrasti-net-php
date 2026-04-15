<?php
declare(strict_types=1);

// 1. Autoloader
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

// 2. Debug Mode ON
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 3. Config Load
$config = require __DIR__ . '/../../.env.php';

try {
    $db = new Database($config['db']);
    $pdo = $db->pdo();
} catch (\PDOException $e) {
    http_response_code(500);
    exit('DB Connection failed: ' . $e->getMessage());
}

// 4. App Start
Auth::start();

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

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

// 5. Routing
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

function render(string $view, array $data = []): void {
    global $settingsRepo;
    $globalSettings = Auth::userId() ? $settingsRepo->getSettings(Auth::userId()) : [];
    extract($data);
    $content = __DIR__ . '/../src/Views/' . $view . '.php';
    require __DIR__ . '/../src/Views/layout.php';
}

$page = $_GET['page'] ?? (Auth::userId() ? 'tasks' : 'login');

switch ($page) {
    case 'login': $auth->showLogin(); break;
    case 'register': $auth->showRegister(); break;
    case 'login_post': $auth->login(); break;
    case 'register_post': $auth->register(); break;
    case 'logout': $auth->logout(); break;
    case 'tasks': $tasks->index(); break;
    case 'settings': $settings->index(); break;
    case 'settings_save': $settings->save(); break;
    case 'habits': $habits->index(); break;
    // Add other cases as needed
    default:
        http_response_code(404);
        render('404');
}