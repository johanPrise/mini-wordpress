<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected static $table = 'users';

    /**
     * Trouver un utilisateur par email (utilise la méthode générique)
     */
    public static function findByEmail(string $email): ?array
    {
        return self::findBy('email', $email);
    }

    /**
     * Trouver un utilisateur par token d'activation (utilise la méthode générique)
     */
    public static function findByToken(string $token): ?array
    {
        return self::findBy('token', $token);
    }

    /**
     * Trouver un utilisateur par token de réinitialisation (token non expiré)
     */
    public static function findByResetToken(string $token): ?array
    {
        $stmt = self::getDb()->prepare("SELECT * FROM " . static::$table . " WHERE reset_token = :token AND reset_token_expires_at > NOW()");
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Vérifier si un email existe déjà (utilise la méthode générique)
     */
    public static function emailExists(string $email, ?int $excludeId = null): bool
    {
        return self::exists('email', $email, $excludeId);
    }


    /**
     * Activer le compte de l'utilisateur via email et token
     */
    public static function activate(string $email, string $token): int
    {
        $stmt = self::getDb()->prepare("UPDATE " . static::$table . " SET is_active = 1, token = NULL WHERE email = :email AND token = :token");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Marquer l'utilisateur comme actif (email vérifié)
     */
    public static function verifyEmail(int $id): bool
    {
        $stmt = self::getDb()->prepare("UPDATE " . static::$table . " SET is_active = TRUE, token = NULL WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Définir le token de réinitialisation
     */
    public static function setResetToken(int $id, string $token, int $expiresInMinutes = 60): bool
    {
        $stmt = self::getDb()->prepare("UPDATE " . static::$table . " SET reset_token = :token, reset_token_expires_at = NOW() + make_interval(mins => :minutes) WHERE id = :id");
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->bindValue(':minutes', $expiresInMinutes, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Réinitialiser le mot de passe
     */
    public static function resetPassword(int $id, string $hashedPassword): bool
    {
        $stmt = self::getDb()->prepare("UPDATE " . static::$table . " SET password = :password, reset_token = NULL, reset_token_expires_at = NULL WHERE id = :id");
        $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Récupérer les utilisateurs par rôle (utilise la méthode générique)
     */
    public static function findByRole(string $role): array
    {
        return self::findAllBy('role', $role);
    }

    /**
     * Récupérer les utilisateurs avec pagination (override pour trier par date et exclure le password)
     */
    public static function paginate(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = self::getDb()->prepare("SELECT id, firstname, lastname, email, role, is_active, created_at FROM " . static::$table . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
