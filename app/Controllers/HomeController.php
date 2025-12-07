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
}
