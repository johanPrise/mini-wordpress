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
}