<?php

declare(strict_types=1);

namespace App\Models;

final class Order
{
    public static function create(array $data): int
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO orders (user_id, menu_id, status, people_count, delivery_address, delivery_city, delivery_postal, delivery_date, delivery_time, delivery_place, distance_km, delivery_fee, menu_price, total_price)
             VALUES (:user_id, :menu_id, :status, :people_count, :delivery_address, :delivery_city, :delivery_postal, :delivery_date, :delivery_time, :delivery_place, :distance_km, :delivery_fee, :menu_price, :total_price)'
        );
        $stmt->execute([
            'user_id' => $data['user_id'],
            'menu_id' => $data['menu_id'],
            'status' => $data['status'],
            'people_count' => $data['people_count'],
            'delivery_address' => $data['delivery_address'],
            'delivery_city' => $data['delivery_city'],
            'delivery_postal' => $data['delivery_postal'],
            'delivery_date' => $data['delivery_date'],
            'delivery_time' => $data['delivery_time'],
            'delivery_place' => $data['delivery_place'],
            'distance_km' => $data['distance_km'],
            'delivery_fee' => $data['delivery_fee'],
            'menu_price' => $data['menu_price'],
            'total_price' => $data['total_price'],
        ]);
        $orderId = (int)Database::pdo()->lastInsertId();
        self::addHistory($orderId, $data['status']);
        return $orderId;
    }

    public static function updateEditable(int $id, array $data): void
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE orders
             SET people_count = :people_count,
                 delivery_address = :delivery_address,
                 delivery_city = :delivery_city,
                 delivery_postal = :delivery_postal,
                 delivery_date = :delivery_date,
                 delivery_time = :delivery_time,
                 delivery_place = :delivery_place,
                 distance_km = :distance_km,
                 delivery_fee = :delivery_fee,
                 menu_price = :menu_price,
                 total_price = :total_price
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'people_count' => $data['people_count'],
            'delivery_address' => $data['delivery_address'],
            'delivery_city' => $data['delivery_city'],
            'delivery_postal' => $data['delivery_postal'],
            'delivery_date' => $data['delivery_date'],
            'delivery_time' => $data['delivery_time'],
            'delivery_place' => $data['delivery_place'],
            'distance_km' => $data['distance_km'],
            'delivery_fee' => $data['delivery_fee'],
            'menu_price' => $data['menu_price'],
            'total_price' => $data['total_price'],
        ]);
    }

    public static function addHistory(int $orderId, string $status): void
    {
        $stmt = Database::pdo()->prepare('INSERT INTO order_status_history (order_id, status) VALUES (:order_id, :status)');
        $stmt->execute(['order_id' => $orderId, 'status' => $status]);
    }

    public static function byUser(int $userId): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT o.*, m.title AS menu_title FROM orders o JOIN menus m ON m.id = o.menu_id WHERE o.user_id = :user_id ORDER BY o.created_at DESC'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT o.*, m.title AS menu_title, m.min_people, m.base_price
             FROM orders o
             JOIN menus m ON m.id = o.menu_id
             WHERE o.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public static function findWithUserEmail(int $id): ?array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT o.*, m.title AS menu_title, u.email AS user_email
             FROM orders o
             JOIN menus m ON m.id = o.menu_id
             JOIN users u ON u.id = o.user_id
             WHERE o.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public static function history(int $orderId): array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM order_status_history WHERE order_id = :order_id ORDER BY created_at ASC');
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    public static function updateStatus(int $id, string $status): void
    {
        $stmt = Database::pdo()->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $id]);
        self::addHistory($id, $status);
    }

    public static function cancel(int $id, string $reason, string $contactMode): void
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE orders SET status = "annulee", cancellation_reason = :reason, cancellation_contact_mode = :contact_mode WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'reason' => $reason,
            'contact_mode' => $contactMode,
        ]);
        self::addHistory($id, 'annulee');
    }

    public static function allByFilters(?string $status = null, ?string $clientEmail = null): array
    {
        $sql = 'SELECT o.*, m.title AS menu_title, u.email AS user_email
                FROM orders o
                JOIN menus m ON m.id = o.menu_id
                JOIN users u ON u.id = o.user_id';

        $clauses = [];
        $params = [];

        if ($status !== null && $status !== '') {
            $clauses[] = 'o.status = :status';
            $params['status'] = $status;
        }

        if ($clientEmail !== null && $clientEmail !== '') {
            $clauses[] = 'u.email LIKE :client_email';
            $params['client_email'] = '%' . $clientEmail . '%';
        }

        if (!empty($clauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }

        $sql .= ' ORDER BY o.created_at DESC';

        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function allByStatus(?string $status = null): array
    {
        return self::allByFilters($status, null);
    }

    public static function revenueByMenu(?int $menuId = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $sql = 'SELECT o.menu_id, m.title AS menu_title, COUNT(o.id) AS orders_count, COALESCE(SUM(o.total_price), 0) AS revenue
                FROM orders o
                JOIN menus m ON m.id = o.menu_id
                WHERE o.status <> "annulee"';
        $params = [];

        if ($menuId !== null && $menuId > 0) {
            $sql .= ' AND o.menu_id = :menu_id';
            $params['menu_id'] = $menuId;
        }

        if ($dateFrom !== null && $dateFrom !== '') {
            $sql .= ' AND o.delivery_date >= :date_from';
            $params['date_from'] = $dateFrom;
        }

        if ($dateTo !== null && $dateTo !== '') {
            $sql .= ' AND o.delivery_date <= :date_to';
            $params['date_to'] = $dateTo;
        }

        $sql .= ' GROUP BY o.menu_id, m.title ORDER BY revenue DESC';

        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function totalRevenue(?int $menuId = null, ?string $dateFrom = null, ?string $dateTo = null): float
    {
        $sql = 'SELECT COALESCE(SUM(o.total_price), 0) AS total_revenue FROM orders o WHERE o.status <> "annulee"';
        $params = [];

        if ($menuId !== null && $menuId > 0) {
            $sql .= ' AND o.menu_id = :menu_id';
            $params['menu_id'] = $menuId;
        }

        if ($dateFrom !== null && $dateFrom !== '') {
            $sql .= ' AND o.delivery_date >= :date_from';
            $params['date_from'] = $dateFrom;
        }

        if ($dateTo !== null && $dateTo !== '') {
            $sql .= ' AND o.delivery_date <= :date_to';
            $params['date_to'] = $dateTo;
        }

        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return (float)($row['total_revenue'] ?? 0);
    }
}
