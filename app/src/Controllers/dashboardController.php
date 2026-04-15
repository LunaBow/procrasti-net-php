<?php
declare(strict_types=1);

namespace Controllers;

use Repos\DashboardRepo;

final class DashboardController
{
    public function __construct(private DashboardRepo $repo)
    {
    }

    public function index(): void
    {
        // Load dashboard data from the repository.
        $dashboard = $this->repo->all();

        // Render the view with the loaded data.
        render('categories', ['categories' => $dashboard]);
    }
}