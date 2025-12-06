<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Page;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index(): void
    {
        // Récupérer les pages publiées pour affichage
        $pages = Page::allPublished();

        // Récupérer les pages du menu
        $menuPages = Page::getMenuPages();

        $this->render('front/home', [
            'title' => 'Accueil',
            'pages' => $pages,
            'menuPages' => $menuPages
        ]);
    }

    /**
     * Afficher une page par son slug
     */
    public function show(string $slug): void
    {
        $page = Page::findBySlug($slug);

        if (!$page) {
            http_response_code(404);
            $this->render('front/home', [
                'title' => 'Page non trouvée',
                'error' => 'La page demandée n\'existe pas.',
                'menuPages' => Page::getMenuPages()
            ]);
            return;
        }

        // Vérifier si la page est publiée
        if ($page['status'] !== 'published') {
            http_response_code(404);
            $this->render('front/home', [
                'title' => 'Page non trouvée',
                'error' => 'La page demandée n\'existe pas.',
                'menuPages' => Page::getMenuPages()
            ]);
            return;
        }

        $this->render('front/page', [
            'title' => $page['title'],
            'page' => $page,
            'menuPages' => Page::getMenuPages()
        ]);
    }
}
