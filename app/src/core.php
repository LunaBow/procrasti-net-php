<?php
// app/src/core.php

/**
 * Connect to the database using settings from .env.php
 */
function db_connect($cfg) {
    // PDO is the standard way to connect to databases in PHP safely.
    // The DSN (Data Source Name) tells PHP which database type and host to use.
    $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['name']};charset={$cfg['charset']}";
    try {
        return new PDO($dsn, $cfg['user'], $cfg['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw errors if something goes wrong
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Return results as easy-to-use arrays
            PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements for better security
        ]);
    } catch (PDOException $e) {
        // If connection fails, stop everything and show why
        exit('DB Connection failed: ' . $e->getMessage());
    }
}

/**
 * Start the session if it hasn't been started yet
 */
function auth_start() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

/**
 * Get the currently logged-in user's ID
 */
function auth_user_id() {
    auth_start();
    // Returns the ID if logged in, or null if not
    return isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : null;
}

/**
 * Log in a user by saving their ID in the session
 */
function auth_login($uid) {
    auth_start();
    // AI Help: Regenerating the session ID prevents "Session Fixation" attacks.
    session_regenerate_id(true);
    $_SESSION['uid'] = $uid;
}

/**
 * Log out the user and clear all session data
 */
function auth_logout() {
    auth_start();
    $_SESSION = []; // Clear all session variables
    // Clear the session cookie from the browser
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

/**
 * Redirect to login page if the user is not logged in
 */
function auth_require() {
    if (!auth_user_id()) {
        header('Location: ?page=login');
        exit;
    }
}

/**
 * Generate or get a CSRF token to prevent form spoofing
 */
function csrf_token() {
    auth_start();
    if (empty($_SESSION['csrf'])) {
        // AI Help: random_bytes creates a secure random string that's hard to guess.
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

/**
 * Verify that the submitted CSRF token matches the one in our session
 */
function csrf_verify($token) {
    auth_start();
    // AI Help: hash_equals is used to compare strings safely without "timing attacks".
    if (!$token || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
        http_response_code(403);
        exit('CSRF check failed');
    }
}

/**
 * Escapes HTML to prevent XSS (Cross-Site Scripting) attacks
 */
function e($s) {
    return htmlspecialchars((string)($s ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Set or get a "flash" message (a message that disappears after reading it once)
 */
function flash($key, $set = null) {
    auth_start();
    if ($set !== null) {
        $_SESSION['flash'][$key] = $set;
        return null;
    }
    $val = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]); // Delete after use
    return $val;
}

/**
 * Renders a view file within the main layout
 */
function render($view, $data = []) {
    global $pdo; // Use the global database connection
    $globalSettings = [];
    $uid = auth_user_id();
    
    // Fetch user settings if logged in so they can be used in the layout
    if ($uid) {
        $st = $pdo->prepare("SELECT * FROM user_settings WHERE user_id = ?");
        $st->execute([$uid]);
        $globalSettings = $st->fetch() ?: [];
        
        // Use default values if settings are missing
        $globalSettings = array_merge([
            'allow_gamification' => 1,
            'privacy_mode' => 0,
            'sarcastic_comments' => 0,
            'hand_drawn_mode' => 0,
            'leet_speak' => 0,
        ], $globalSettings);
    }
    
    // AI Help: extract() turns array keys into variables (e.g., ['title' => 'Home'] becomes $title = 'Home').
    extract($data);
    
    $content = __DIR__ . '/Views/' . $view . '.php';
    // Require the layout which will include the $content file
    require __DIR__ . '/Views/layout.php';
}
