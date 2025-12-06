<?php
namespace App\Core;

use PDO;
use PDOException;
use App\Config\Database as DBConfig;

class Model{
    protected static $table;

    protected static function getDb(){
        return Database::getInstance();
    }

    public static function all(){
        $stmt = self::getDb()->prepare("SELECT * FROM " . static::$table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     public static function find($id){
        $stmt = self::getDb()-> prepare("SELECT * FROM " . static::$table . " WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data){
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $stmt = self::getDb()->prepare("INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return self::getDb()->lastInsertId();
    }

    public static function update($id, $data){
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ', ');
        $stmt = self::getDb()->prepare("UPDATE " . static::$table . " SET $set WHERE id = :id");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public static function delete($id){
        $stmt = self::getDb()->prepare("DELETE FROM " . static::$table . " WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Compter le nombre total d'enregistrements
     */
    public static function count(): int
    {
        $stmt = self::getDb()->prepare("SELECT COUNT(*) FROM " . static::$table);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Récupérer les enregistrements avec pagination
     */
    public static function paginate(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = self::getDb()->prepare("SELECT * FROM " . static::$table . " ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Trouver un enregistrement par une colonne spécifique
     */
    public static function findBy(string $column, $value): ?array
    {
        $stmt = self::getDb()->prepare("SELECT * FROM " . static::$table . " WHERE $column = :value");
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Trouver tous les enregistrements par une colonne spécifique
     */
    public static function findAllBy(string $column, $value): array
    {
        $stmt = self::getDb()->prepare("SELECT * FROM " . static::$table . " WHERE $column = :value");
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifier si un enregistrement existe avec une valeur donnée
     */
    public static function exists(string $column, $value, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM " . static::$table . " WHERE $column = :value";
        if ($excludeId) {
            $sql .= " AND id != :excludeId";
        }
        $stmt = self::getDb()->prepare($sql);
        $stmt->bindValue(':value', $value);
        if ($excludeId) {
            $stmt->bindValue(':excludeId', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}

