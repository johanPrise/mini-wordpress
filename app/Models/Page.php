<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Page extends Model
{
    protected static $table = 'pages';

    /**
     * Trouver une page par son slug (utilise la méthode générique)
     */
    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }

    /**
     * Récupérer toutes les pages publiées
     */
    public static function allPublished(): array
    {
        return self::findAllBy('status', 'published');
    }

    /**
     * Récupérer les pages pour le menu (publiées et dans le menu)
     */
    public static function getMenuPages(): array
    {
        $stmt = self::getDb()->prepare("SELECT id, title, slug FROM " . static::$table . " WHERE status = 'published' AND in_menu = 1 ORDER BY menu_order ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifier si un slug existe déjà (utilise la méthode générique)
     */
    public static function slugExists(string $slug, ?int $excludeId = null): bool
    {
        return self::exists('slug', $slug, $excludeId);
    }

    /**
     * Générer un slug unique à partir du titre
     */
    public static function generateSlug(string $title, ?int $excludeId = null): string
    {
        // Convertir en minuscules et remplacer les espaces par des tirets
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        $originalSlug = $slug;
        $counter = 1;

        while (self::slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Récupérer les pages avec pagination (override pour trier par date)
     */
    public static function paginate(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = self::getDb()->prepare("SELECT * FROM " . static::$table . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}