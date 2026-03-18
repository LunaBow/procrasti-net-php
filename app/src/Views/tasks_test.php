<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Simple tasks page for testing
$title = 'Tasks Test';

try {
    // Include necessary files
    require_once __DIR__ . '/../../.env.php';
    require_once __DIR__ . '/../Core/Database.php';
    require_once __DIR__ . '/../Core/Auth.php';
    require_once __DIR__ . '/../Repos/TaskRepo.php';

    // Start auth session
    \Core\Auth::start();

    // Create database connection
    $db = new \Core\Database($config['db']);
    $pdo = $db->pdo();

    // Test database connection
    $userId = \Core\Auth::userId();
    echo "<h1>Tasks Test Page</h1>";
    echo "<p>User ID: $userId</p>";
    echo "<p>Database connection: OK</p>";

    // Test TaskRepo
    $taskRepo = new \Repos\TaskRepo($pdo);
    $tasks = $taskRepo->allByUserSimple($userId);
    echo "<p>Found " . count($tasks) . " tasks (simple query)</p>";

    if (!empty($tasks)) {
        echo "<ul>";
        foreach ($tasks as $task) {
            echo "<li>" . htmlspecialchars($task['title']) . " - " . htmlspecialchars($task['status']) . " - Category: " . ($task['category_id'] ?? 'none') . "</li>";
        }
        echo "</ul>";
    }

    // Now try the full query
    try {
        $tasksFull = $taskRepo->allByUser($userId);
        echo "<p>Full query also works! Found " . count($tasksFull) . " tasks with categories</p>";
    } catch (Exception $e) {
        echo "<p>Full query failed: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>Error code: " . $e->getCode() . "</p>";
    }

} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>File: " . htmlspecialchars($e->getFile()) . " Line: " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>
