<?php
namespace App\Core;

class Session
{
    /**
     * Démarrer la session si elle n'est pas déjà active
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Définir une valeur en session
     */
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Récupérer une valeur avec valeur par défaut optionnelle
     */
    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Vérifier si une clé existe
     */
    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Supprimer une clé spécifique
     */
    public static function remove($key)
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Gestion des messages Flash (ajouté pour compatibilité hybride)
     */
    public static function setFlash($key, $message)
    {
        self::set('flash_' . $key, $message);
    }

    public static function getFlash($key)
    {
        $message = self::get('flash_' . $key);
        self::remove('flash_' . $key);
        return $message;
    }

    /**
     * Destruction complète et sécurisée (Méthode de Houda)
     */
    public static function destroy()
    {
        self::start();

        // 1. Vider le tableau $_SESSION
        $_SESSION = [];

        // 2. Supprimer le cookie de session (Sécurité critique)
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

        // 3. Détruire la session côté serveur
        session_destroy();
    }
}
