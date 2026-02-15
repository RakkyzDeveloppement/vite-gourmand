<?php

declare(strict_types=1);

namespace App\Models;

use PDOException;

final class Menu
{
    public static function all(array $filters = [], bool $includeInactive = false): array
    {
        $sql = 'SELECT m.*, t.name AS theme, r.name AS regime FROM menus m 
                JOIN themes t ON t.id = m.theme_id 
                JOIN regimes r ON r.id = m.regime_id WHERE 1=1';
        $params = [];

        if (!$includeInactive) {
            $sql .= ' AND m.is_active = 1';
        }

        if (!empty($filters['theme'])) {
            $sql .= ' AND t.name = :theme';
            $params['theme'] = $filters['theme'];
        }
        if (!empty($filters['regime'])) {
            $sql .= ' AND r.name = :regime';
            $params['regime'] = $filters['regime'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= ' AND m.base_price <= :max_price';
            $params['max_price'] = $filters['max_price'];
        }
        if (!empty($filters['min_people'])) {
            $sql .= ' AND m.min_people >= :min_people';
            $params['min_people'] = $filters['min_people'];
        }
        if (!empty($filters['price_from']) && !empty($filters['price_to'])) {
            $sql .= ' AND m.base_price BETWEEN :price_from AND :price_to';
            $params['price_from'] = $filters['price_from'];
            $params['price_to'] = $filters['price_to'];
        }

        $sql .= ' ORDER BY m.created_at DESC';

        $stmt = Database::pdo()->prepare($sql);
        try {
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'Unknown column') && str_contains($e->getMessage(), 'is_active')) {
                $fallbackSql = str_replace(' AND m.is_active = 1', '', $sql);
                $fallbackStmt = Database::pdo()->prepare($fallbackSql);
                $fallbackStmt->execute($params);
                return $fallbackStmt->fetchAll();
            }
            throw $e;
        }
    }

    public static function themes(): array
    {
        $stmt = Database::pdo()->query('SELECT id, name FROM themes ORDER BY name ASC');
        return $stmt->fetchAll();
    }

    public static function regimes(): array
    {
        $stmt = Database::pdo()->query('SELECT id, name FROM regimes ORDER BY name ASC');
        return $stmt->fetchAll();
    }

    public static function find(int $id, bool $includeInactive = false): ?array
    {
        $sql = 'SELECT m.*, t.name AS theme, r.name AS regime FROM menus m 
             JOIN themes t ON t.id = m.theme_id 
             JOIN regimes r ON r.id = m.regime_id WHERE m.id = :id';

        if (!$includeInactive) {
            $sql .= ' AND m.is_active = 1';
        }

        $stmt = Database::pdo()->prepare($sql);
        try {
            $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'Unknown column') && str_contains($e->getMessage(), 'is_active')) {
                $fallbackSql = str_replace(' AND m.is_active = 1', '', $sql);
                $stmt = Database::pdo()->prepare($fallbackSql);
                $stmt->execute(['id' => $id]);
            } else {
                throw $e;
            }
        }

        $menu = $stmt->fetch();
        return $menu ?: null;
    }

    public static function images(int $menuId): array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM menu_images WHERE menu_id = :menu_id');
        $stmt->execute(['menu_id' => $menuId]);
        return $stmt->fetchAll();
    }

    public static function dishes(int $menuId): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT d.*, GROUP_CONCAT(a.name SEPARATOR ", ") AS allergens 
             FROM menu_dishes md 
             JOIN dishes d ON d.id = md.dish_id 
             LEFT JOIN dish_allergens da ON da.dish_id = d.id 
             LEFT JOIN allergens a ON a.id = da.allergen_id 
             WHERE md.menu_id = :menu_id 
             GROUP BY d.id'
        );
        $stmt->execute(['menu_id' => $menuId]);
        return $stmt->fetchAll();
    }

    public static function save(array $data, ?int $id = null): int
    {
        if ($id) {
            $stmt = Database::pdo()->prepare(
                'UPDATE menus SET title=:title, description=:description, theme_id=:theme_id, regime_id=:regime_id, min_people=:min_people, base_price=:base_price, conditions_text=:conditions_text, stock=:stock WHERE id=:id'
            );
            $stmt->execute([
                'id' => $id,
                'title' => $data['title'],
                'description' => $data['description'],
                'theme_id' => $data['theme_id'],
                'regime_id' => $data['regime_id'],
                'min_people' => $data['min_people'],
                'base_price' => $data['base_price'],
                'conditions_text' => $data['conditions_text'],
                'stock' => $data['stock'],
            ]);
            return $id;
        }

        $stmt = Database::pdo()->prepare(
            'INSERT INTO menus (title, description, theme_id, regime_id, min_people, base_price, conditions_text, stock) VALUES (:title, :description, :theme_id, :regime_id, :min_people, :base_price, :conditions_text, :stock)'
        );
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'theme_id' => $data['theme_id'],
            'regime_id' => $data['regime_id'],
            'min_people' => $data['min_people'],
            'base_price' => $data['base_price'],
            'conditions_text' => $data['conditions_text'],
            'stock' => $data['stock'],
        ]);
        return (int)Database::pdo()->lastInsertId();
    }

    public static function delete(int $id): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM menus WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function setActive(int $id, bool $isActive): void
    {
        $stmt = Database::pdo()->prepare('UPDATE menus SET is_active = :is_active WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'is_active' => $isActive ? 1 : 0,
        ]);
    }
}
