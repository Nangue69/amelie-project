<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $host    = Config::get('db.host', 'localhost');
        $name    = Config::get('db.name', '');
        $charset = Config::get('db.charset', 'utf8mb4');
        $port    = (int) Config::get('db.port', 3306);

        try {
            self::$pdo = new PDO(
                "mysql:host={$host};port={$port};dbname={$name};charset={$charset}",
                (string) Config::get('db.user', ''),
                (string) Config::get('db.pass', ''),
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            error_log('Conexion a base de datos fallida: ' . $e->getMessage());
            http_response_code(503);
            exit('No se ha podido conectar con la base de datos.');
        }

        return self::$pdo;
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $statement = self::pdo()->prepare($sql);
        $statement->execute($params);

        return $statement;
    }

    public static function all(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function one(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();

        return $row === false ? null : $row;
    }

    public static function value(string $sql, array $params = [], mixed $default = null): mixed
    {
        $value = self::query($sql, $params)->fetchColumn();

        return $value === false ? $default : $value;
    }

    public static function insert(string $table, array $data): int
    {
        $columns = array_keys($data);
        $fields  = implode(', ', array_map(static fn (string $c): string => "`{$c}`", $columns));
        $holders = implode(', ', array_map(static fn (string $c): string => ":{$c}", $columns));

        self::query("INSERT INTO `{$table}` ({$fields}) VALUES ({$holders})", $data);

        return (int) self::pdo()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $assignments = [];

        foreach (array_keys($data) as $column) {
            $assignments[] = "`{$column}` = :set_{$column}";
        }

        $params = [];

        foreach ($data as $column => $value) {
            $params["set_{$column}"] = $value;
        }

        return self::query(
            "UPDATE `{$table}` SET " . implode(', ', $assignments) . " WHERE {$where}",
            $params + $whereParams
        )->rowCount();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        return self::query("DELETE FROM `{$table}` WHERE {$where}", $params)->rowCount();
    }

    public static function transaction(callable $callback): mixed
    {
        $pdo = self::pdo();
        $pdo->beginTransaction();

        try {
            $result = $callback();
            $pdo->commit();

            return $result;
        } catch (\Throwable $e) {
            $pdo->rollBack();

            throw $e;
        }
    }

    /**
     * Devuelve la lista de marcadores y los parametros para una clausula IN.
     */
    public static function inClause(array $values, string $prefix = 'in'): array
    {
        $holders = [];
        $params  = [];

        foreach (array_values($values) as $index => $value) {
            $key           = "{$prefix}{$index}";
            $holders[]     = ":{$key}";
            $params[$key]  = $value;
        }

        return [implode(', ', $holders), $params];
    }
}
