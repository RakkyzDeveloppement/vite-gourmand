<?php

declare(strict_types=1);

namespace App\Models;

final class Review
{
    public static function validated(): array
    {
        $stmt = Database::pdo()->query('SELECT r.*, u.first_name, u.last_name FROM reviews r JOIN orders o ON o.id = r.order_id JOIN users u ON u.id = o.user_id WHERE r.is_validated = 1 ORDER BY r.created_at DESC');
        return $stmt->fetchAll();
    }

    public static function create(int $orderId, int $rating, string $comment): void
    {
        $stmt = Database::pdo()->prepare('INSERT INTO reviews (order_id, rating, comment, is_validated) VALUES (:order_id, :rating, :comment, 0)');
        $stmt->execute([
            'order_id' => $orderId,
            'rating' => $rating,
            'comment' => $comment,
        ]);
    }

    public static function validate(int $id, bool $accept): void
    {
        $stmt = Database::pdo()->prepare('UPDATE reviews SET is_validated = :val WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'val' => $accept ? 1 : 0,
        ]);
    }

    public static function pending(): array
    {
        $stmt = Database::pdo()->query('SELECT r.*, u.email FROM reviews r JOIN orders o ON o.id = r.order_id JOIN users u ON u.id = o.user_id WHERE r.is_validated = 0 ORDER BY r.created_at DESC');
        return $stmt->fetchAll();
    }
}
