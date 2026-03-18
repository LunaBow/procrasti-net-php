<?php
declare(strict_types=1);

namespace Controllers;

use Core\Auth;
use Repos\TaskRepo;

final class CalendarController {
    public function __construct(private TaskRepo $repo) {}

    public function index(): void {
        Auth::requireLogin();
        render('calendar');
    }

    public function export(): void {
        Auth::requireLogin();
        
        $fromDate = $_GET['from'] ?? date('Y-m-d');
        $toDate = $_GET['to'] ?? date('Y-m-d');

        $events = $this->repo->byDateRange(Auth::userId(), $fromDate, $toDate);

        $ics = $this->generateICS($events);

        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="procrasti-net_' . $fromDate . '_' . $toDate . '.ics"');
        echo $ics;
        exit;
    }

    private function generateICS(array $events): string {
        $lines = [
            "BEGIN:VCALENDAR",
            "VERSION:2.0",
            "PRODID:-//Procrasti-NET//EN"
        ];

        foreach ($events as $e) {
            $start = $e['due_at'] ?? $e['created_at'];
            if (!$start) continue;

            // Format date for ICS: YYYYMMDDTHHMMSSZ (assuming UTC for simplicity or using local time without Z)
            $dt = str_replace(['-', ':', ' '], ['', '', 'T'], substr($start, 0, 19)) . 'Z';
            
            $summary = $this->icsEscape($e['title'] ?? 'Task');

            $lines[] = "BEGIN:VEVENT";
            $lines[] = "UID:" . $e['id'] . "@procrasti-net";
            $lines[] = "DTSTART:" . $dt;
            $lines[] = "DTEND:" . $dt;
            $lines[] = "SUMMARY:" . $summary;
            $lines[] = "END:VEVENT";
        }

        $lines[] = "END:VCALENDAR";
        return implode("\r\n", $lines);
    }

    private function icsEscape(string $s): string {
        return str_replace(["\\", ",", ";", "\n"], ["\\\\", "\\,", "\\;", "\\n"], $s);
    }
}
