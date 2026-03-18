<?php
declare(strict_types=1);

namespace Core;

final class Csrf {
    public static function token(): string {
        Auth::start();
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['csrf'];
    }

    public static function verify(?string $token): void {
        Auth::start();
        if (!$token || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
            http_response_code(403);
            exit('CSRF failed');
        }
    }
}