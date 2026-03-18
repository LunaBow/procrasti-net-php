<?php
declare(strict_types=1);

namespace Controllers;

use Core\Auth;
use Core\Csrf;

final class AuthController
{
    public function __construct(private \PDO $pdo)
    {
    }

    public function showLogin(): void
    {
        //  show the login form
        render('login');
    }

    public function showRegister(): void
    {
        //  show the registration form
        render('register');
    }

    public function register(): void
    {
        // make sure no one is trying to trick me
        Csrf::verify($_POST['csrf'] ?? null);

        $email = trim((string)($_POST['email'] ?? ''));
        $name = trim((string)($_POST['display_name'] ?? ''));
        $pass = (string)($_POST['password'] ?? '');

        // Basic validation, don't let them submit blank
        if ($email === '' || $name === '' || $pass === '') {
            flash('error', 'Missing fields.');
            header('Location: ?page=register');
            exit;
        }

        // Hashing the password so we don't store plaintext.
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        try {
            // Using prepared statements so we don't get SQL injected.
            $st = $this->pdo->prepare("INSERT INTO users (email, display_name, password_hash) VALUES (?, ?, ?)");
            $st->execute([$email, $name, $hash]);
            
            // Log them in immediately after they register
            Auth::login((int)$this->pdo->lastInsertId());
            header('Location: ?page=tasks');
        } catch (\PDOException $e) {
            // Probably a duplicate email constraint failed in the DB
            flash('error', 'Email already used (probably :3).');
            header('Location: ?page=register');
        }
    }

    public function login(): void
    {
        Csrf::verify($_POST['csrf'] ?? null);

        $email = trim((string)($_POST['email'] ?? ''));
        $pass = (string)($_POST['password'] ?? '');

        // Grab the user by email
        $st = $this->pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
        $st->execute([$email]);
        $u = $st->fetch();

        // Check if user exists AND password matches the hash we stored
        if (!$u || !password_verify($pass, $u['password_hash'])) {
            flash('error', 'Wrong credentials.');
            header('Location: ?page=login');
            exit;
        }

        // Setup the session
        Auth::login((int)$u['id']);
        header('Location: ?page=tasks');
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: ?page=login');
    }
}