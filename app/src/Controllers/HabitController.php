<?php
declare(strict_types=1);

namespace Controllers;

use Core\Auth;
use Core\Csrf;
use Repos\HabitRepo;

final class HabitController {
    public function __construct(private HabitRepo $repo) {}

    public function index(): void {
        Auth::requireLogin();
        $habits = $this->repo->allByUser(Auth::userId());
        render('habits', ['habits' => $habits]);
    }

    public function create(): void {
        Auth::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF protection so bots don't fill your habit list with spam
            Csrf::verify($_POST['csrf'] ?? null);
            
            $name = $_POST['name'] ?? '';
            if ($name) {
                $this->repo->create(Auth::userId(), $name);
            }
        }
        header('Location: ?page=habits');
        exit;
    }
    
    public function check(): void {
        Auth::requireLogin();
        
        // CSRF protection for habit checks too
        Csrf::verify($_POST['csrf'] ?? null);

        $habitId = (int)($_POST['id'] ?? 0);
        $date = $_POST['date'] ?? date('Y-m-d');
        
        if ($habitId > 0) {
            $success = $this->repo->check(Auth::userId(), $habitId, $date);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => $success]);
                exit;
            }
        }
        
        header('Location: ?page=habits');
        exit;
    }
}
