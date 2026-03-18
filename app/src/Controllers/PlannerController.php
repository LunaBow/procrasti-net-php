<?php
declare(strict_types=1);

namespace Controllers;

use Core\Auth;
use Repos\TaskRepo;

final class PlannerController {
    public function __construct(private TaskRepo $repo) {}

    public function index(): void {
        Auth::requireLogin();
        
        // Handle HTMX/Fetch partial requests or standard GET
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        
        $tasks = $this->repo->byDate(Auth::userId(), $selectedDate);
        
        render('planner', [
            'tasks' => $tasks,
            'selectedDate' => $selectedDate
        ]);
    }
}
