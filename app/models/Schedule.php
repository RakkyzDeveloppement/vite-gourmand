<?php

declare(strict_types=1);

namespace App\Models;

final class Schedule
{
    public static function all(): array
    {
        $stmt = Database::pdo()->query('SELECT * FROM schedules ORDER BY day_of_week ASC');
        return $stmt->fetchAll();
    }

    public static function save(array $data, ?int $id = null): int
    {
        if ($id !== null) {
            $stmt = Database::pdo()->prepare(
                'UPDATE schedules SET day_of_week=:day_of_week, open_time=:open_time, close_time=:close_time, open_time2=:open_time2, close_time2=:close_time2, is_closed=:is_closed WHERE id=:id'
            );
            $stmt->execute([
                'id' => $id,
                'day_of_week' => $data['day_of_week'],
                'open_time' => $data['open_time'],
                'close_time' => $data['close_time'],
                'open_time2' => $data['open_time2'],
                'close_time2' => $data['close_time2'],
                'is_closed' => $data['is_closed'],
            ]);
            return $id;
        }

        $stmt = Database::pdo()->prepare(
            'INSERT INTO schedules (day_of_week, open_time, close_time, open_time2, close_time2, is_closed) VALUES (:day_of_week, :open_time, :close_time, :open_time2, :close_time2, :is_closed)'
        );
        $stmt->execute([
            'day_of_week' => $data['day_of_week'],
            'open_time' => $data['open_time'],
            'close_time' => $data['close_time'],
            'open_time2' => $data['open_time2'],
            'close_time2' => $data['close_time2'],
            'is_closed' => $data['is_closed'],
        ]);
        return (int)Database::pdo()->lastInsertId();
    }
}
