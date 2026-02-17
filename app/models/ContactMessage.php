<?php

declare(strict_types=1);

namespace App\Models;

final class ContactMessage
{
    public static function create(string $email, string $title, string $message): void
    {
        $stmt = Database::pdo()->prepare('INSERT INTO contact_messages (email, title, message) VALUES (:email, :title, :message)');
        $stmt->execute([
            'email' => $email,
            'title' => $title,
            'message' => $message,
        ]);
    }
}
