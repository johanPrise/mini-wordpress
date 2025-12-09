<?php
namespace App\Core;

use App\Core\Session;

class Controller
{
    /**
     * Rendre une vue avec des données
     */
    public function render($view, $data = []): void
    {
        extract($data);
        require_once "../app/Views/$view.php";
    }

    /**
     * Rediriger vers une URL
     */
    public function redirect($url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Définir un message flash
     */
    public function flash($key, $message): void
    {
        Session::set($key, $message);
    }

    /**
     * Récupérer et supprimer un message flash
     */
    public function getFlash($key)
    {
        $message = Session::get($key);
        Session::remove($key);
        return $message;
    }

    // ==================== GESTION DES RÔLES ====================

    /**
     * Vérifier si l'utilisateur est connecté
     */
    protected function requireAuth(): void
    {
        if (!Session::get('user')) {
            $this->redirect('/login');
        }
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    protected function requireAdmin(): void
    {
        $this->requireAuth();
        $user = Session::get('user');
        if ($user['role'] !== 'admin') {
            $this->flash('error', 'Accès réservé aux administrateurs.');
            $this->redirect('/admin');
        }
    }

    /**
     * Vérifier si l'utilisateur est admin ou editor
     */
    protected function requireEditor(): void
    {
        $this->requireAuth();
        $user = Session::get('user');
        if (!in_array($user['role'], ['admin', 'editor'])) {
            $this->flash('error', 'Accès réservé aux éditeurs et administrateurs.');
            $this->redirect('/admin');
        }
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    protected function currentUser(): ?array
    {
        return Session::get('user');
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    protected function hasRole(string $role): bool
    {
        $user = Session::get('user');
        return $user && $user['role'] === $role;
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    protected function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Vérifier si l'utilisateur est editor ou admin
     */
    protected function isEditor(): bool
    {
        $user = Session::get('user');
        return $user && in_array($user['role'], ['admin', 'editor']);
    }
}