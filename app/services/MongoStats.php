<?php

declare(strict_types=1);

namespace App\Services;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;

final class MongoStats
{
    private static ?Client $client = null;
    private static string $lastError = '';

    public static function lastError(): string
    {
        return self::$lastError;
    }

    private static function setError(string $message): void
    {
        self::$lastError = $message;
    }

    private static function client(): ?Client
    {
        if (!class_exists(Client::class)) {
            self::setError('Extension ou librairie MongoDB non chargee (MongoDB\\Client introuvable).');
            return null;
        }

        if (self::$client === null) {
            try {
                $cfg = app()['db']['mongo'];
                self::$client = new Client((string)$cfg['uri']);
            } catch (\Throwable $e) {
                self::setError('Connexion Mongo impossible: ' . $e->getMessage());
                return null;
            }
        }

        return self::$client;
    }

    public static function recordOrder(int $menuId, string $menuTitle, float $total): void
    {
        $client = self::client();
        if (!$client) {
            return;
        }

        try {
            $dbName = (string)app()['db']['mongo']['database'];
            $collection = $client->selectCollection($dbName, 'menu_stats');
            $collection->updateOne(
                ['menu_id' => $menuId],
                [
                    '$setOnInsert' => ['menu_title' => $menuTitle],
                    '$inc' => ['orders_count' => 1, 'revenue' => $total],
                    '$set' => ['updated_at' => new UTCDateTime()],
                ],
                ['upsert' => true]
            );
        } catch (\Throwable $e) {
            self::setError('Ecriture Mongo impossible: ' . $e->getMessage());
        }
    }

    public static function rebuildFromRows(array $rows): bool
    {
        $client = self::client();
        if (!$client) {
            return false;
        }

        try {
            $dbName = (string)app()['db']['mongo']['database'];
            $collection = $client->selectCollection($dbName, 'menu_stats');

            $collection->deleteMany([]);

            foreach ($rows as $row) {
                $collection->insertOne([
                    'menu_id' => (int)($row['menu_id'] ?? 0),
                    'menu_title' => (string)($row['menu_title'] ?? 'Menu'),
                    'orders_count' => (int)($row['orders_count'] ?? 0),
                    'revenue' => (float)($row['revenue'] ?? 0),
                    'updated_at' => new UTCDateTime(),
                ]);
            }

            self::setError('');
            return true;
        } catch (\Throwable $e) {
            self::setError('Synchronisation Mongo impossible: ' . $e->getMessage());
            return false;
        }
    }

    public static function getStats(): array
    {
        $client = self::client();
        if (!$client) {
            return [];
        }

        try {
            $dbName = (string)app()['db']['mongo']['database'];
            $collection = $client->selectCollection($dbName, 'menu_stats');
            $cursor = $collection->find([], ['sort' => ['orders_count' => -1]]);
            self::setError('');
            return iterator_to_array($cursor);
        } catch (\Throwable $e) {
            self::setError('Lecture Mongo impossible: ' . $e->getMessage());
            return [];
        }
    }
}
