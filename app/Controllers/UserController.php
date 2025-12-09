<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

/**
 * UserController - Gestion des utilisateurs (CRUD) côté admin
 * Accès réservé aux administrateurs uniquement
 */
class UserController extends Controller
{

    /**
     * Liste des utilisateurs (admin)
     * GET /admin/users
     */
    public function index(): void
    {
        $this->requireAdmin();

        $users = User::all();

        $this->render('admin/users/index', [
            'title' => 'Gestion des utilisateurs',
            'users' => $users,
            'success' => $this->getFlash('success'),
            'error' => $this->getFlash('error')
        ]);
    }

    /**
     * Afficher le formulaire de création
     * GET /admin/users/create
     */
    public function create(): void
    {
        $this->requireAdmin();

        $this->render('admin/users/create', [
            'title' => 'Créer un utilisateur',
            'error' => $this->getFlash('error')
        ]);
    }

    /**
     * Enregistrer un nouvel utilisateur
     * POST /admin/users/store
     */
    public function store(): void
    {
        $this->requireAdmin();

        $firstname = trim($_POST['firstname'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        // Validation
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            $this->flash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/admin/users/create');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('error', 'Email invalide.');
            $this->redirect('/admin/users/create');
            return;
        }

        if (User::emailExists($email)) {
            $this->flash('error', 'Cet email existe déjà.');
            $this->redirect('/admin/users/create');
            return;
        }

        // Créer l'utilisateur
        $userId = User::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'is_active' => true // Actif par défaut si créé par admin
        ]);

        if ($userId) {
            $this->flash('success', 'Utilisateur créé avec succès.');
            $this->redirect('/admin/users');
        } else {
            $this->flash('error', 'Erreur lors de la création.');
            $this->redirect('/admin/users/create');
        }
    }

    /**
     * Afficher le formulaire d'édition
     * GET /admin/users/{id}/edit
     */
    public function edit(int $id): void
    {
        $this->requireAdmin();

        $user = User::find($id);

        if (!$user) {
            $this->flash('error', 'Utilisateur introuvable.');
            $this->redirect('/admin/users');
            return;
        }

        $this->render('admin/users/edit', [
            'title' => 'Modifier l\'utilisateur',
            'user' => $user,
            'error' => $this->getFlash('error')
        ]);
    }

    /**
     * Mettre à jour un utilisateur
     * POST /admin/users/{id}/update
     */
    public function update(int $id): void
    {
        $this->requireAdmin();

        $user = User::find($id);

        if (!$user) {
            $this->flash('error', 'Utilisateur introuvable.');
            $this->redirect('/admin/users');
            return;
        }

        $firstname = trim($_POST['firstname'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        // Validation
        if (empty($firstname) || empty($lastname) || empty($email)) {
            $this->flash('error', 'Le prénom, nom et l\'email sont obligatoires.');
            $this->redirect("/admin/users/$id/edit");
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('error', 'Email invalide.');
            $this->redirect("/admin/users/$id/edit");
            return;
        }

        if (User::emailExists($email, $id)) {
            $this->flash('error', 'Cet email existe déjà.');
            $this->redirect("/admin/users/$id/edit");
            return;
        }

        // Préparer les données
        $data = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'role' => $role
        ];

        // Mettre à jour le mot de passe seulement si renseigné
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $updated = User::update($id, $data);

        if ($updated) {
            $this->flash('success', 'Utilisateur mis à jour avec succès.');
        } else {
            $this->flash('error', 'Erreur lors de la mise à jour.');
        }

        $this->redirect('/admin/users');
    }

    /**
     * Supprimer un utilisateur
     * POST /admin/users/{id}/delete
     */
    public function delete(int $id): void
    {
        $this->requireAdmin();

        $user = User::find($id);

        if (!$user) {
            $this->flash('error', 'Utilisateur introuvable.');
            $this->redirect('/admin/users');
            return;
        }

        // Empêcher la suppression de son propre compte
        $currentUser = Session::get('user');
        if ($currentUser['id'] == $id) {
            $this->flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            $this->redirect('/admin/users');
            return;
        }

        $deleted = User::delete($id);

        if ($deleted) {
            $this->flash('success', 'Utilisateur supprimé avec succès.');
        } else {
            $this->flash('error', 'Erreur lors de la suppression.');
        }

        $this->redirect('/admin/users');
    }
}
