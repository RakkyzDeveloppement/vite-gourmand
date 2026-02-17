<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class User
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT u.*, r.name AS role FROM users u JOIN roles r ON r.id = u.role_id WHERE u.email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT u.*, r.name AS role FROM users u JOIN roles r ON r.id = u.role_id WHERE u.id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function allEmployees(): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT u.id, u.first_name, u.last_name, u.email, u.is_active, u.created_at
             FROM users u
             JOIN roles r ON r.id = u.role_id
             WHERE r.name = "employe"
             ORDER BY u.created_at DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function create(array $data, string $role = 'utilisateur'): int
    {
        $roleId = self::roleId($role);
        $stmt = Database::pdo()->prepare(
            'INSERT INTO users (role_id, first_name, last_name, email, password_hash, phone, address_line1, postal_code, city, is_active)
             VALUES (:role_id, :first_name, :last_name, :email, :password_hash, :phone, :address_line1, :postal_code, :city, 1)'
        );
        $stmt->execute([
            'role_id' => $roleId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone' => $data['phone'],
            'address_line1' => $data['address_line1'],
            'postal_code' => $data['postal_code'],
            'city' => $data['city'],
        ]);
        return (int)Database::pdo()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE users SET first_name=:first_name, last_name=:last_name, phone=:phone, address_line1=:address_line1, postal_code=:postal_code, city=:city WHERE id=:id'
        );
        $stmt->execute([
            'id' => $id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'address_line1' => $data['address_line1'],
            'postal_code' => $data['postal_code'],
            'city' => $data['city'],
        ]);
    }

    public static function disable(int $id): void
    {
        $stmt = Database::pdo()->prepare('UPDATE users SET is_active = 0 WHERE id=:id');
        $stmt->execute(['id' => $id]);
    }

    public static function roleId(string $name): int
    {
        $stmt = Database::pdo()->prepare('SELECT id FROM roles WHERE name = :name LIMIT 1');
        $stmt->execute(['name' => $name]);
        $role = $stmt->fetch();
        return (int)($role['id'] ?? 0);
    }
}
