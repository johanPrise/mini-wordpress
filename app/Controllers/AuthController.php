<?php
/**
 * =====================================================================
 * 🔐 AUTH CONTROLLER - Gère l'authentification
 * =====================================================================
 * 
 * Ce controller gère toutes les requêtes liées à l'authentification :
 * - Connexion (login)
 * - Inscription (register)
 * - Déconnexion (logout)
 * - Mot de passe oublié
 * - Réinitialisation du mot de passe
 * 
 * 📚 EXERCICE D'APPRENTISSAGE :
 *    Ce fichier est prêt à être complété ! Suis les instructions
 *    dans GUIDE_APPRENTISSAGE.md (Niveau 8)
 * 
 * =====================================================================
 */

// Inclusion du modèle User pour interagir avec la base de données
// require_once = inclut le fichier une seule fois (évite les doublons)
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    
    /**
     * 📋 Affiche le formulaire de connexion
     * 
     * Route : GET /login
     * 
     * 💡 Cette méthode inclut simplement la vue du formulaire
     */
    public function showLogin() {
        require __DIR__ . '/../Views/auth/login.php';
    }
    
    /**
     * 🔐 Traite la soumission du formulaire de connexion
     * 
     * Route : POST /login/submit
     * 
     * 💡 Cette méthode doit :
     *    1. Récupérer email et password depuis $_POST
     *    2. Vérifier les identifiants avec User::findByEmail()
     *    3. Si OK : créer la session et rediriger
     *    4. Si KO : afficher une erreur
     */
    public function login() {
        // TODO: Implémenter la logique de connexion
        echo "🔐 Traitement de la connexion (à implémenter)";
    }
    
    /**
     * 🚪 Déconnecte l'utilisateur
     * 
     * Route : GET /logout
     * 
     * 💡 Cette méthode doit :
     *    1. Détruire la session avec session_destroy()
     *    2. Rediriger vers la page d'accueil
     */
    public function logout() {
        // Détruit toutes les données de session
        session_destroy();
        
        // Redirige vers la page d'accueil
        // header() envoie un en-tête HTTP de redirection
        header('Location: /');
        
        // exit est OBLIGATOIRE après header() pour arrêter le script
        // Sinon le code continue à s'exécuter !
        exit;
    }
    
    /**
     * 📋 Affiche le formulaire d'inscription
     * 
     * Route : GET /register
     */
    public function showRegister() {
        // TODO: Inclure la vue auth/register.php
        echo "📋 Formulaire d'inscription (à implémenter)";
    }
    
    /**
     * ✅ Traite la soumission du formulaire d'inscription
     * 
     * Route : POST /register/submit
     * 
     * 💡 Cette méthode doit :
     *    1. Récupérer les données depuis $_POST
     *    2. Valider les données (email valide, password assez long, etc.)
     *    3. Hasher le mot de passe avec password_hash()
     *    4. Créer l'utilisateur avec User::create()
     *    5. Envoyer un email de vérification
     *    6. Rediriger avec un message de succès
     */
    public function register() {
        // TODO: Implémenter la logique d'inscription
        echo "✅ Traitement de l'inscription (à implémenter)";
    }
    
    /**
     * 📧 Vérifie l'email de l'utilisateur
     * 
     * Route : GET /verify-email?token=xxx
     */
    public function verifyEmail() {
        // TODO: Implémenter la vérification d'email
        echo "📧 Vérification d'email (à implémenter)";
    }
    
    /**
     * 📋 Affiche le formulaire "Mot de passe oublié"
     * 
     * Route : GET /forgot-password
     */
    public function showForgotPassword() {
        // TODO: Inclure la vue auth/forgot-password.php
        echo "📋 Formulaire mot de passe oublié (à implémenter)";
    }
    
    /**
     * 📧 Traite la demande de réinitialisation de mot de passe
     * 
     * Route : POST /forgot-password/submit
     */
    public function forgotPassword() {
        // TODO: Implémenter l'envoi d'email de réinitialisation
        echo "📧 Envoi email réinitialisation (à implémenter)";
    }
    
    /**
     * 📋 Affiche le formulaire de nouveau mot de passe
     * 
     * Route : GET /reset-password?token=xxx
     */
    public function showResetPassword() {
        // TODO: Inclure la vue pour définir un nouveau mot de passe
        echo "📋 Formulaire nouveau mot de passe (à implémenter)";
    }
    
    /**
     * ✅ Traite le nouveau mot de passe
     * 
     * Route : POST /reset-password/submit
     */
    public function resetPassword() {
        // TODO: Implémenter la mise à jour du mot de passe
        echo "✅ Mise à jour du mot de passe (à implémenter)";
    }
}
