<?php
/**
 * =====================================================================
 * 👤 MODEL USER - Représente un utilisateur dans la base de données
 * =====================================================================
 * 
 * Ce fichier définit la classe User qui permet d'interagir avec
 * la table "users" de la base de données.
 * 
 * 🎯 Un Model a deux responsabilités :
 *    1. Représenter les données (propriétés = colonnes de la table)
 *    2. Fournir des méthodes pour manipuler ces données (CRUD)
 * 
 * 📚 EXERCICE D'APPRENTISSAGE :
 *    Ce fichier est prêt à être complété ! Suis les instructions
 *    dans GUIDE_APPRENTISSAGE.md (Niveau 7)
 * 
 * =====================================================================
 */

// Inclusion de la classe Database pour accéder à la BDD
require_once __DIR__ . '/../Core/Database.php';

class User {
    
    // ================================
    // 📊 PROPRIÉTÉS (= colonnes de la table)
    // ================================
    
    /**
     * @var int|null L'identifiant unique de l'utilisateur
     * 
     * 'public' = accessible depuis l'extérieur ($user->id)
     * Correspond à la colonne 'id' de la table (clé primaire)
     */
    public $id;
    
    /**
     * @var string L'adresse email de l'utilisateur (unique)
     */
    public $email;
    
    /**
     * @var string Le mot de passe hashé
     * 
     * ⚠️ JAMAIS stocker le mot de passe en clair !
     * Utilise password_hash() pour hasher
     */
    public $password;
    
    /**
     * @var string Le nom d'affichage de l'utilisateur
     */
    public $name;
    
    /**
     * @var string Le rôle de l'utilisateur (ex: 'admin', 'user')
     */
    public $role;
    
    /**
     * @var string|null Token de vérification d'email
     */
    public $email_token;
    
    /**
     * @var bool L'email a-t-il été vérifié ?
     */
    public $email_verified;
    
    /**
     * @var string|null Token de réinitialisation de mot de passe
     */
    public $reset_token;
    
    /**
     * @var string|null Date d'expiration du token de reset
     */
    public $reset_token_expires;
    
    /**
     * @var string Date de création du compte
     */
    public $created_at;
    
    // ================================
    // 📖 MÉTHODES DE LECTURE (Read)
    // ================================
    
    /**
     * 📋 Récupère TOUS les utilisateurs
     * 
     * 'static' = on appelle sur la classe : User::findAll()
     *            et non sur un objet : $user->findAll()
     * 
     * @return User[] Un tableau d'objets User
     * 
     * 💡 EXEMPLE D'UTILISATION :
     *    $users = User::findAll();
     *    foreach ($users as $user) {
     *        echo $user->name;
     *    }
     */
    public static function findAll() {
        // 1. Obtenir la connexion unique à la BDD (Singleton)
        $db = Database::getInstance();
        
        // 2. Préparer la requête SQL
        // prepare() est plus sûr que query() car il évite les injections SQL
        $stmt = $db->prepare("SELECT * FROM users ORDER BY created_at DESC");
        
        // 3. Exécuter la requête
        $stmt->execute();
        
        // 4. Récupérer les résultats
        // PDO::FETCH_CLASS = chaque ligne devient un objet de la classe User
        // Les colonnes de la BDD sont automatiquement mappées aux propriétés
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }
    
    /**
     * 🔍 Trouve UN utilisateur par son ID
     * 
     * @param int $id L'identifiant de l'utilisateur
     * @return User|false L'utilisateur trouvé ou false si non trouvé
     * 
     * 💡 EXEMPLE D'UTILISATION :
     *    $user = User::findById(42);
     *    if ($user) {
     *        echo "Bonjour " . $user->name;
     *    }
     */
    public static function findById($id) {
        $db = Database::getInstance();
        
        // Le "?" est un placeholder - il sera remplacé par $id
        // Cela évite les injections SQL !
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        
        // execute() remplace les ? par les valeurs du tableau
        $stmt->execute([$id]);
        
        // fetch() = UN seul résultat (vs fetchAll = plusieurs)
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }
    
    /**
     * 🔍 Trouve UN utilisateur par son email
     * 
     * @param string $email L'adresse email recherchée
     * @return User|false L'utilisateur trouvé ou false si non trouvé
     * 
     * 💡 Utilisé pour la connexion et les vérifications d'unicité
     */
    public static function findByEmail($email) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }
    
    // ================================
    // ✏️ MÉTHODES D'ÉCRITURE (Create/Update/Delete)
    // ================================
    
    /**
     * ➕ Crée un nouvel utilisateur dans la base de données
     * 
     * @param array $data Les données de l'utilisateur
     * @return int L'ID du nouvel utilisateur créé
     * 
     * 💡 EXEMPLE D'UTILISATION :
     *    $id = User::create([
     *        'email' => 'test@example.com',
     *        'password' => password_hash('secret', PASSWORD_DEFAULT),
     *        'name' => 'Jean Dupont',
     *        'role' => 'user'
     *    ]);
     */
    public static function create($data) {
        $db = Database::getInstance();
        
        // Les :name sont des placeholders nommés (plus lisibles)
        $stmt = $db->prepare("
            INSERT INTO users (email, password, name, role, email_token, created_at)
            VALUES (:email, :password, :name, :role, :email_token, NOW())
        ");
        
        $stmt->execute([
            ':email' => $data['email'],
            ':password' => $data['password'],  // DOIT être hashé !
            ':name' => $data['name'],
            ':role' => $data['role'] ?? 'user',
            ':email_token' => $data['email_token'] ?? null
        ]);
        
        // lastInsertId() retourne l'ID auto-incrémenté du dernier INSERT
        return $db->lastInsertId();
    }
    
    /**
     * 📝 Met à jour un utilisateur existant
     * 
     * @param int $id L'ID de l'utilisateur à modifier
     * @param array $data Les nouvelles données
     * @return bool Succès ou échec
     */
    public static function update($id, $data) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("
            UPDATE users 
            SET email = :email, 
                name = :name, 
                role = :role
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':email' => $data['email'],
            ':name' => $data['name'],
            ':role' => $data['role']
        ]);
    }
    
    /**
     * 🗑️ Supprime un utilisateur
     * 
     * @param int $id L'ID de l'utilisateur à supprimer
     * @return bool Succès ou échec
     * 
     * ⚠️ ATTENTION : Cette action est irréversible !
     */
    public static function delete($id) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
