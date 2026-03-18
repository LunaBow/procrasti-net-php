<?php
declare(strict_types=1);

namespace Controllers;

use Core\Auth;
use Core\Csrf;
use Repos\TaskRepo;
use Repos\SettingsRepo;

final class TaskController {
    public function __construct(
        private TaskRepo $repo,
        private ?SettingsRepo $settingsRepo = null
    ) {}

    public function index(): void {
        Auth::requireLogin();
        $userId = Auth::userId();
        
        // Load all the tasks for this user so they can see what they are ignoring today
        $tasks = $this->repo->allByUser($userId);

        // Load settings to check for Privacy Mode
        $settings = [];
        if ($this->settingsRepo) {
            $settings = $this->settingsRepo->getSettings($userId);
        }
        
        render('tasks', [
            'tasks' => $tasks,
            'settings' => $settings
        ]);
    }

    public function create(): void {
        Auth::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF before creating to stop attackers
            Csrf::verify($_POST['csrf'] ?? null);
            
            $title = $_POST['title'] ?? '';
            $categoryId = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;
            $dueDate = isset($_POST['due_date']) && $_POST['due_date'] !== '' ? $_POST['due_date'] : null;
            $reminderAt = isset($_POST['reminder_at']) && $_POST['reminder_at'] !== '' ? $_POST['reminder_at'] : null;

            if ($title) {
                // Chuck it in the DB
                $this->repo->create(Auth::userId(), $title, $categoryId, $dueDate, $reminderAt);
            }
        }
        // Send them back to the list
        header('Location: ?page=tasks');
        exit;
    }
    
    public function toggle(): void {
        Auth::requireLogin();
        
        // Verify CSRF before toggling to stop attackers from marking your shit done
        Csrf::verify($_POST['csrf'] ?? null);
        
        $taskId = (int)($_POST['id'] ?? 0);
        
        if ($taskId > 0) {
            // Flip the status in the DB (done -> todo, todo -> done)
            $result = $this->repo->toggleDone(Auth::userId(), $taskId);

            // If JS called this via fetch() (asynchronous data transfer!), send back JSON instead of reloading the whole page.
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            }
        }
        
        // Fallback for browsers without JS (or if they hit enter on a form)
        header('Location: ?page=tasks');
        exit;
    }
}
