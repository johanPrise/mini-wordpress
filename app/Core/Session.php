<?php
namespace App\Core;

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key)
    {
        self::start();
        return $_SESSION[$key] ?? null;
    }

    public static function remove(string $key)
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function requireAdmin()
    {
        self::start();

        if (empty($_SESSION["user"])) {
            // pas connecté / redirection login
            header("Location: /login");
            exit;
        }

        if (empty($_SESSION["user"]["role"]) || $_SESSION["user"]["role"] !== "admin") {
            // connecté mais pas ADMIN
            http_response_code(403);
            die("Accès interdit : vous n’êtes pas administrateur.");
        }
    }

    public static function destroy()
    {
        self::start();

        // Supprime toutes les données de session
        $_SESSION = [];

        // Supprime le cookie de session (sécurité importante)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Détruit
        session_destroy();
    }
}