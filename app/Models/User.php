<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected static $table = "users";

    public static function findByEmail(string $email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        return self::queryOne($sql, ["email" => $email]);
    }

    public static function create(array $data)
    {
        $sql = "INSERT INTO users (firstname, lastname, email, password, token, is_active)
                VALUES (:firstname, :lastname, :email, :password, :token, FALSE)";
                
        return self::query($sql, $data, false);
    }

    public static function activate(string $email, string $token)
    {
        $sql = "UPDATE users
                SET is_active = TRUE, token = NULL
                WHERE email = :email AND token = :token";

        // query() retourne un PDOStatement SEULEMENT pour UPDATE
        $stmt = self::query($sql, [
            "email" => $email,
            "token" => $token
        ], false);

        if (!$stmt) {
            return 0;
        }

        return $stmt->rowCount();   // retourne le nb de ligne modifiées
    }

    public static function all()
    {
        return self::query("SELECT * FROM users ORDER BY id DESC");
    }

    public static function setResetToken(string $email, string $token)
    {
        $sql = "UPDATE users SET reset_token = :token WHERE email = :email";
        return self::query($sql, ["token" => $token, "email" => $email], false);
    }

    public static function updatePasswordWithToken(string $email, string $token, string $password)
    {
        // On vérifie si le token existe
        $sql = "SELECT * FROM users WHERE email = :email AND reset_token = :token LIMIT 1";
        $user = self::queryOne($sql, ["email" => $email, "token" => $token]);

        if (!$user) return false;

        // Modifier le mot de passe et supprimer le token
        $sql = "UPDATE users 
                SET password = :password, reset_token = NULL 
                WHERE email = :email";

        self::query($sql, [
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "email" => $email
        ], false);

        return true;
    }
}