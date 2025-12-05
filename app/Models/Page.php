<?php
/**
 * =====================================================================
 * 📄 MODEL PAGE - Représente une page de contenu
 * =====================================================================
 * 
 * Ce fichier définit la classe Page qui permet d'interagir avec
 * la table "pages" de la base de données.
 * 
 * Une page a :
 * - Un titre (title)
 * - Un slug (URL amicale, ex: "a-propos")
 * - Un contenu HTML (content)
 * - Un statut de publication (published)
 * 
 * 📚 EXERCICE D'APPRENTISSAGE :
 *    Ce fichier suit le même modèle que User.php
 *    Compare les deux pour comprendre le pattern !
 * 
 * =====================================================================
 */

require_once __DIR__ . '/../Core/Database.php';

class Page {
    
    // ================================
    // 📊 PROPRIÉTÉS
    // ================================
    
    public $id;
    public $title;       // Titre de la page
    public $slug;        // URL amicale (ex: "contact")
    public $content;     // Contenu HTML de la page
    public $published;   // true = visible, false = brouillon
    public $created_at;
    public $updated_at;
    
    // ================================
    // 📖 MÉTHODES DE LECTURE
    // ================================
    
    /**
     * 📋 Récupère toutes les pages
     * 
     * @return Page[] Tableau de toutes les pages
     */
    public static function findAll() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM pages ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Page');
    }
    
    /**
     * 📋 Récupère uniquement les pages publiées
     * 
     * @return Page[] Tableau des pages publiées
     * 
     * 💡 Utilisé pour le menu du site public
     */
    public static function findPublished() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM pages WHERE published = 1 ORDER BY title");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Page');
    }
    
    /**
     * 🔍 Trouve une page par son ID
     * 
     * @param int $id L'identifiant de la page
     * @return Page|false
     */
    public static function findById($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Page');
        return $stmt->fetch();
    }
    
    /**
     * 🔍 Trouve une page par son slug
     * 
     * @param string $slug L'URL amicale (ex: "a-propos")
     * @return Page|false
     * 
     * 💡 Utilisé pour afficher une page depuis son URL
     *    Ex: /a-propos → Page::findBySlug('a-propos')
     */
    public static function findBySlug($slug) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM pages WHERE slug = ? AND published = 1");
        $stmt->execute([$slug]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Page');
        return $stmt->fetch();
    }
    
    // ================================
    // ✏️ MÉTHODES D'ÉCRITURE
    // ================================
    
    /**
     * ➕ Crée une nouvelle page
     * 
     * @param array $data ['title', 'slug', 'content', 'published']
     * @return int L'ID de la nouvelle page
     */
    public static function create($data) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("
            INSERT INTO pages (title, slug, content, published, created_at, updated_at)
            VALUES (:title, :slug, :content, :published, NOW(), NOW())
        ");
        
        $stmt->execute([
            ':title' => $data['title'],
            ':slug' => $data['slug'] ?? self::generateSlug($data['title']),
            ':content' => $data['content'],
            ':published' => $data['published'] ?? 0
        ]);
        
        return $db->lastInsertId();
    }
    
    /**
     * 📝 Met à jour une page existante
     * 
     * @param int $id L'ID de la page
     * @param array $data Les nouvelles données
     * @return bool
     */
    public static function update($id, $data) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("
            UPDATE pages 
            SET title = :title,
                slug = :slug,
                content = :content,
                published = :published,
                updated_at = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':content' => $data['content'],
            ':published' => $data['published'] ?? 0
        ]);
    }
    
    /**
     * 🗑️ Supprime une page
     * 
     * @param int $id L'ID de la page à supprimer
     * @return bool
     */
    public static function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM pages WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // ================================
    // 🔧 UTILITAIRES
    // ================================
    
    /**
     * 🔤 Génère un slug à partir d'un titre
     * 
     * @param string $title Le titre de la page
     * @return string Le slug généré
     * 
     * 💡 EXEMPLE :
     *    "À propos de nous !" → "a-propos-de-nous"
     */
    public static function generateSlug($title) {
        // Convertit en minuscules
        $slug = strtolower($title);
        
        // Remplace les caractères accentués
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
        
        // Remplace tout ce qui n'est pas alphanumérique par des tirets
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        
        // Supprime les tirets en début et fin
        $slug = trim($slug, '-');
        
        return $slug;
    }
}
