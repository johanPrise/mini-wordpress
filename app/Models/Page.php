<?php
namespace App\Models;

use App\Core\Model;

class Page extends Model
{
    protected static string $table = "pages";

    public static function findBySlug(string $slug)
    {
        return self::query(
            "SELECT * FROM pages WHERE slug = :slug",
            ["slug" => $slug],
            true
        );
    }
}
