<?php
/**
 * =====================================================================
 * 🏠 HOME CONTROLLER - Gère la page d'accueil
 * =====================================================================
 * 
 * Ce controller gère toutes les requêtes liées à la page d'accueil
 * du site public (front office).
 * 
 * 📚 EXERCICE D'APPRENTISSAGE :
 *    Ce fichier est prêt à être complété ! Suis les instructions
 *    dans GUIDE_APPRENTISSAGE.md (Exercice 4.1)
 * 
 * =====================================================================
 */

/**
 * 🎯 Définition de la classe HomeController
 * 
 * 'class' = mot-clé PHP pour définir une classe
 * Une classe regroupe des données (propriétés) et des comportements (méthodes)
 */
class HomeController {
    
    /**
     * 📄 Méthode index() - Affiche la page d'accueil
     * 
     * Cette méthode est appelée quand un utilisateur accède à "/"
     * (voir routes/routes.yaml : "/" => action: index)
     * 
     * 'public' = cette méthode est accessible depuis l'extérieur de la classe
     * 'function' = mot-clé pour définir une fonction/méthode
     */
    public function index() {
        /**
         * 💡 require inclut et exécute le fichier de vue
         * 
         * __DIR__ = chemin absolu du dossier contenant CE fichier
         * /../Views/ = on remonte d'un dossier puis on va dans Views
         * 
         * La vue aura accès à toutes les variables définies ici !
         */
        require __DIR__ . '/../Views/front/home.php';
    }
}
