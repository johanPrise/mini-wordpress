<?php
/**
 * =====================================================================
 * 👥 USER CONTROLLER - Gestion des utilisateurs (Admin)
 * =====================================================================
 * 
 * Ce controller gère les opérations CRUD sur les utilisateurs :
 * - Create (Créer)
 * - Read (Lire/Lister)
 * - Update (Modifier)
 * - Delete (Supprimer)
 * 
 * 🔒 SÉCURITÉ : Toutes ces routes doivent vérifier que l'utilisateur
 *              est connecté ET qu'il a les droits admin !
 * 
 * 📚 EXERCICE D'APPRENTISSAGE :
 *    Ce fichier est prêt à être complété ! Suis les instructions
 *    dans GUIDE_APPRENTISSAGE.md (Exercice Final B)
 * 
 * =====================================================================
 */

require_once __DIR__ . '/../Models/User.php';

class UserController {
    
    /**
     * 📋 Liste tous les utilisateurs
     * 
     * Route : GET /admin/users
     * 
     * 💡 EXERCICE : Complète cette méthode pour :
     *    1. Récupérer tous les utilisateurs avec User::findAll()
     *    2. Passer les données à la vue
     *    3. Afficher la liste dans un tableau HTML
     */
    public function index() {
        // TODO: Récupérer la liste des utilisateurs
        // $users = User::findAll();
        
        // TODO: Inclure la vue admin/users/index.php
        echo "📋 Liste des utilisateurs (à implémenter)";
        echo "<br><small>📚 Voir GUIDE_APPRENTISSAGE.md - Exercice Final B</small>";
    }
    
    /**
     * 📝 Affiche le formulaire de création d'utilisateur
     * 
     * Route : GET /admin/users/create
     */
    public function showCreate() {
        // TODO: Inclure la vue admin/users/create.php
        echo "📝 Formulaire de création utilisateur (à implémenter)";
    }
    
    /**
     * ✅ Traite la création d'un utilisateur
     * 
     * Route : POST /admin/users/create/submit
     * 
     * 💡 Cette méthode doit :
     *    1. Récupérer les données depuis $_POST
     *    2. Valider les données
     *    3. Hasher le mot de passe
     *    4. Créer l'utilisateur dans la BDD
     *    5. Rediriger vers la liste
     */
    public function create() {
        // TODO: Implémenter la création d'utilisateur
        echo "✅ Création d'utilisateur (à implémenter)";
    }
    
    /**
     * 📝 Affiche le formulaire de modification d'utilisateur
     * 
     * Route : GET /admin/users/edit?id=X
     */
    public function showEdit() {
        // Récupère l'ID depuis l'URL (?id=X)
        // $_GET contient les paramètres de l'URL
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo "❌ ID utilisateur manquant";
            return;
        }
        
        // TODO: Récupérer l'utilisateur avec User::findById($id)
        // TODO: Inclure la vue admin/users/edit.php
        echo "📝 Formulaire de modification utilisateur #" . htmlspecialchars($id) . " (à implémenter)";
    }
    
    /**
     * ✅ Traite la modification d'un utilisateur
     * 
     * Route : POST /admin/users/edit/submit
     */
    public function edit() {
        // TODO: Implémenter la modification d'utilisateur
        echo "✅ Modification d'utilisateur (à implémenter)";
    }
    
    /**
     * 🗑️ Supprime un utilisateur
     * 
     * Route : GET /admin/users/delete?id=X
     * 
     * ⚠️ ATTENTION : En production, cette action devrait :
     *    - Être accessible uniquement en POST (pas en GET)
     *    - Demander une confirmation
     *    - Vérifier les droits de l'utilisateur connecté
     */
    public function delete() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo "❌ ID utilisateur manquant";
            return;
        }
        
        // TODO: Supprimer l'utilisateur avec User::delete($id)
        // TODO: Rediriger vers la liste
        echo "🗑️ Suppression utilisateur #" . htmlspecialchars($id) . " (à implémenter)";
    }
}
