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
     * Trouver un utilisateur par nom d'utilisateur (utilise la méthode générique)
     */
    public static function findByUsername(string $username): ?array
    {
        return self::findBy('username', $username);
    }

    /**
     * Trouver un utilisateur par token de vérification (utilise la méthode générique)
     */
    public static function findByVerificationToken(string $token): ?array
    {
        return self::findBy('verification_token', $token);
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
     * Vérifier si un nom d'utilisateur existe déjà (utilise la méthode générique)
     */
    public static function usernameExists(string $username, ?int $excludeId = null): bool
    {
        return self::exists('username', $username, $excludeId);
    }

    /**
     * Marquer l'email comme vérifié
     */
    public static function verifyEmail(int $id): bool
    {
        $stmt = self::getDb()->prepare("UPDATE " . static::$table . " SET email_verified_at = NOW(), verification_token = NULL WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Définir le token de réinitialisation
     */
    public static function setResetToken(int $id, string $token, int $expiresInMinutes = 60): bool
    {
        $stmt = self::getDb()->prepare("UPDATE " . static::$table . " SET reset_token = :token, reset_token_expires_at = DATE_ADD(NOW(), INTERVAL :minutes MINUTE) WHERE id = :id");
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
        $stmt = self::getDb()->prepare("SELECT id, username, email, role, email_verified_at, created_at FROM " . static::$table . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
