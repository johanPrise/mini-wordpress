<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::findBySlug($slug);

        if (!$page) {
            http_response_code(404);
            echo "<h1>Page introuvable</h1>";
            return;
        }

        $this->view("front/page", ["page" => $page]);
    }
}
