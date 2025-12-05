<?php
/**
 * =====================================================================
 * 📄 PAGE CONTROLLER - Gestion des pages (Front & Admin)
 * =====================================================================
 * 
 * Ce controller gère les pages de contenu :
 * - Affichage des pages côté public
 * - CRUD des pages côté admin
 * 
 * 📚 EXERCICE D'APPRENTISSAGE :
 *    Ce fichier est prêt à être complété ! Suis les instructions
 *    dans GUIDE_APPRENTISSAGE.md (Exercice Final A)
 * 
 * =====================================================================
 */

require_once __DIR__ . '/../Models/Page.php';

class PageController {
    
    // ================================
    // 🌐 FRONT OFFICE (Pages publiques)
    // ================================
    
    /**
     * 📄 Affiche une page par son slug
     * 
     * Un "slug" est l'URL amicale d'une page
     * Exemple : "a-propos" pour la page "À propos"
     * 
     * Route : GET /{slug} (ex: /a-propos)
     */
    public function show() {
        // Récupère le slug depuis l'URL
        $slug = $_GET['slug'] ?? null;
        
        // TODO: Récupérer la page avec Page::findBySlug($slug)
        // TODO: Inclure la vue front/page.php
        echo "📄 Affichage de la page (à implémenter)";
    }
    
    // ================================
    // 🔒 BACK OFFICE (Administration)
    // ================================
    
    /**
     * 📋 Liste toutes les pages
     * 
     * Route : GET /admin/pages
     */
    public function index() {
        // TODO: Récupérer toutes les pages avec Page::findAll()
        // TODO: Inclure la vue admin/pages/index.php
        echo "📋 Liste des pages (à implémenter)";
    }
    
    /**
     * 📝 Affiche le formulaire de création de page
     * 
     * Route : GET /admin/pages/create
     */
    public function showCreate() {
        // TODO: Inclure la vue admin/pages/create.php
        echo "📝 Formulaire de création de page (à implémenter)";
    }
    
    /**
     * ✅ Traite la création d'une page
     * 
     * Route : POST /admin/pages/create/submit
     * 
     * 💡 Cette méthode doit :
     *    1. Récupérer les données (titre, contenu, slug)
     *    2. Générer automatiquement le slug si non fourni
     *    3. Créer la page dans la BDD
     *    4. Rediriger vers la liste
     */
    public function create() {
        // TODO: Implémenter la création de page
        echo "✅ Création de page (à implémenter)";
    }
    
    /**
     * 📝 Affiche le formulaire de modification de page
     * 
     * Route : GET /admin/pages/edit?id=X
     */
    public function showEdit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo "❌ ID page manquant";
            return;
        }
        
        // TODO: Récupérer la page avec Page::findById($id)
        // TODO: Inclure la vue admin/pages/edit.php
        echo "📝 Formulaire de modification page #" . htmlspecialchars($id) . " (à implémenter)";
    }
    
    /**
     * ✅ Traite la modification d'une page
     * 
     * Route : POST /admin/pages/edit/submit
     */
    public function edit() {
        // TODO: Implémenter la modification de page
        echo "✅ Modification de page (à implémenter)";
    }
    
    /**
     * 🗑️ Supprime une page
     * 
     * Route : GET /admin/pages/delete?id=X
     */
    public function delete() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo "❌ ID page manquant";
            return;
        }
        
        // TODO: Supprimer la page avec Page::delete($id)
        // TODO: Rediriger vers la liste
        echo "🗑️ Suppression page #" . htmlspecialchars($id) . " (à implémenter)";
    }
}
