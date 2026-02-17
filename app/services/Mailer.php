<?php

declare(strict_types=1);

namespace App\Services;

final class Mailer
{
    public static function send(string $to, string $subject, string $message): void
    {
        $from = app()['config']['mail_from'] ?? 'no-reply@vite-gourmand.local';
        $headers = 'From: ' . $from . "\r\n" . 'Content-Type: text/plain; charset=utf-8';

        $sent = false;
        if (function_exists('mail')) {
            $sent = @mail($to, $subject, $message, $headers);
        }

        if (!$sent) {
            $logDir = __DIR__ . '/../../storage/logs';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }
            $entry = sprintf("[%s] TO:%s\nSUBJECT:%s\n%s\n\n", date('c'), $to, $subject, $message);
            file_put_contents($logDir . '/mail.log', $entry, FILE_APPEND);
        }
    }
}
