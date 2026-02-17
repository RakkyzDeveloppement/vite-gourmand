<?php

declare(strict_types=1);

namespace App\Models;

final class Dish
{
    public static function all(): array
    {
        $stmt = Database::pdo()->query('SELECT * FROM dishes ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function save(array $data, ?int $id = null): int
    {
        if ($id !== null) {
            $stmt = Database::pdo()->prepare('UPDATE dishes SET name=:name, type=:type, description=:description WHERE id=:id');
            $stmt->execute([
                'id' => $id,
                'name' => $data['name'],
                'type' => $data['type'],
                'description' => $data['description'],
            ]);
            return $id;
        }

        $stmt = Database::pdo()->prepare('INSERT INTO dishes (name, type, description) VALUES (:name, :type, :description)');
        $stmt->execute([
            'name' => $data['name'],
            'type' => $data['type'],
            'description' => $data['description'],
        ]);
        return (int)Database::pdo()->lastInsertId();
    }

    public static function delete(int $id): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM dishes WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
