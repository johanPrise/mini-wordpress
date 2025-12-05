<?php
/**
 * =====================================================================
 * 📚 CLASSE DATABASE - Connexion à la base de données
 * =====================================================================
 * 
 * Cette classe utilise le PATTERN SINGLETON pour s'assurer qu'il n'existe
 * qu'UNE SEULE connexion à la base de données dans toute l'application.
 * 
 * 🎯 Pourquoi Singleton ?
 *    - Évite d'ouvrir plusieurs connexions (coûteux en ressources)
 *    - Garantit que tout le code utilise la même connexion
 *    - Facilite la gestion des transactions
 * 
 * 💡 Comment l'utiliser :
 *    $db = Database::getInstance();
 *    $stmt = $db->prepare("SELECT * FROM users");
 * 
 * =====================================================================
 */
class Database{
    /**
     * 🔒 Stocke l'unique instance de connexion PDO
     * 'private static' = accessible seulement depuis cette classe
     * 'null' = pas encore créée au démarrage
     */
    private static $instance = null;

    /**
     * 🚫 Constructeur PRIVÉ = empêche de faire 'new Database()'
     * C'est la clé du pattern Singleton !
     * Si quelqu'un essaie de créer une instance directement, PHP refusera.
     */
    private function __construct(){}

    /**
     * 🏭 MÉTHODE FACTORY - Seule façon d'obtenir la connexion
     * 
     * 'static' = on l'appelle sur la CLASSE, pas sur un objet
     * Exemple : Database::getInstance() et non $db->getInstance()
     * 
     * @return PDO L'instance unique de connexion à la base de données
     */
    public static function getInstance(){
        // Si aucune connexion n'existe encore...
        if( self::$instance === null ){
            try{
                /**
                 * 🔌 Création de la connexion PDO
                 * 
                 * PDO = PHP Data Objects, interface standard pour bases de données
                 * 
                 * Le DSN (Data Source Name) contient :
                 * - mysql:      Le type de base de données
                 * - host=       Le serveur (localhost = sur cette machine)
                 * - dbname=     Le nom de la base de données
                 */
                self::$instance = new PDO('mysql:host=' . DB_HOST .'; dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
                
                /**
                 * ⚙️ Configuration du mode d'erreur
                 * 
                 * ERRMODE_EXCEPTION = si erreur SQL, PHP lance une Exception
                 * C'est le mode recommandé car il permet de capturer les erreurs
                 * avec try/catch plutôt que de les ignorer silencieusement
                 */
                self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            } catch (Exception $e){
                // ❌ Si la connexion échoue, on arrête tout avec un message
                die('Erreur de connection à la base de données : ' . $e->getMessage());
            }
        }
        // 🔄 Retourne toujours la MÊME instance (créée ou existante)
        return self::$instance;
    }
}