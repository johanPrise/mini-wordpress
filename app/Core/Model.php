<?php
namespace App\Core;

abstract class Model {

    /**
     * Query universelle
     */
    public static function query(string $sql, array $params = [], bool $fetch = true)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        if ($fetch) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        // INSERT / UPDATE / DELETE → return PDOStatement
        return $stmt;
    }

    public static function queryOne(string $sql, array $params = [])
    {
        $db = Database::getInstance();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
