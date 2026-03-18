<?php
$config = require '.env.php';
try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['db']['host'], $config['db']['name'], 'utf8mb4');
    $pdo = new \PDO($dsn, $config['db']['user'], $config['db']['pass'], [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    ]);
    echo "Connected successfully!";
} catch (\Exception $e) {
    echo "Failed: " . $e->getMessage();
}
