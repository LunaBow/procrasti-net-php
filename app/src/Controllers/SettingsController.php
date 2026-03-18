<?php
declare(strict_types=1);

namespace Controllers;

use Core\Auth;
use Core\Csrf;
use Repos\SettingsRepo;

final class SettingsController {
    public function __construct(private SettingsRepo $repo) {}

    public function index(): void {
        Auth::requireLogin();
        
        $userId = Auth::userId();
        $user = $this->repo->getUserInfo($userId);
        $settings = $this->repo->getSettings($userId);
        
        render('settings', [
            'user' => $user,
            'settings' => $settings
        ]);
    }

    public function save(): void {
        Auth::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF protection - ain't nobody hijacking my settings
            Csrf::verify($_POST['csrf'] ?? null);
            
            $userId = Auth::userId();
            
            // Handle Display Name change
            $name = $_POST['display_name'] ?? '';
            if ($name !== '') {
                $this->repo->updateDisplayName($userId, $name);
            }
            
            // Handle fun toggles
            // Checkboxes only send a value if they are checked.
            $gamification = isset($_POST['allow_gamification']) ? 1 : 0;
            $privacy = isset($_POST['privacy_mode']) ? 1 : 0;
            $sarcasm = isset($_POST['sarcastic_comments']) ? 1 : 0;
            $handDrawn = isset($_POST['hand_drawn_mode']) ? 1 : 0;
            $leet = isset($_POST['leet_speak']) ? 1 : 0;
            
            $this->repo->updateSettings($userId, $gamification, $privacy, $sarcasm, $handDrawn, $leet);
            
            flash('success', 'Task failed successfully. Nah, jk, i saved it lol. We ball.');

            // Clear output buffer and redirect
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Location: ?page=settings');
            exit();
        }

        // If not POST, just redirect to settings
        header('Location: ?page=settings');
        exit();
    }
}
