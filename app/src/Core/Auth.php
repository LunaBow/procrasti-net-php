<?php
declare(strict_types=1);

namespace Core;

final class Auth {
    // Boilerplate to make sure sessions are running before we use them.
    public static function start(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    // Tells us if a user is logged in right now. Returns their ID, or null.
    public static function userId(): ?int {
        self::start();
        return isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : null;
    }

    // Handles the actual login process by saving their ID to the session.
    public static function login(int $uid): void {
        self::start();
        // Regenerate the ID to prevent session fixation attacks.
        session_regenerate_id(true);
        $_SESSION['uid'] = $uid;
    }

    // Burns the session to the ground so they are completely logged out.
    public static function logout(): void {
        self::start();
        $_SESSION = [];
        
        // Nuke the session cookie too, just to be thorough.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    // Bouncer function. If you try to access a page you shouldn't, you get kicked to login.
    public static function requireLogin(): void {
        if (!self::userId()) {
            header('Location: ?page=login');
            exit;
        }
    }
}