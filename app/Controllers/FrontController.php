<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Page;
use App\Models\User;

/**
 * FrontController - Toutes les pages frontoffice + dashboard admin
 */
class FrontController extends Controller
{
    // ==================== FRONTOFFICE ====================

    /**
     * Page d'accueil (frontoffice)
     * GET /
     */
    public function index(): void
    {
        $pages = Page::allPublished();
        $menuPages = Page::getMenuPages();

        $this->render('front/home', [
            'title' => 'Accueil',
            'pages' => $pages,
            'menuPages' => $menuPages
        ]);
    }

    /**
     * Afficher une page par son slug (frontoffice)
     * GET /{slug}
     */
    public function show(string $slug): void
    {
        $page = Page::findBySlug($slug);

        if (!$page || $page['status'] !== 'published') {
            http_response_code(404);
            echo "Page non trouvée (404)";
            return;
        }

        $menuPages = Page::getMenuPages();

        $this->render('front/page', [
            'title' => $page['title'],
            'page' => $page,
            'menuPages' => $menuPages
        ]);
    }

    // ==================== BACKOFFICE ====================

    /**
     * Dashboard admin (page d'accueil backoffice)
     * GET /admin
     */
    public function dashboard(): void
    {
        $this->requireAuth();

        $user = Session::get('user');
        $totalPages = Page::count();
        $publishedPages = count(Page::allPublished());

        // Seul admin peut voir le nombre d'utilisateurs
        $totalUsers = $this->isAdmin() ? User::count() : null;

        $this->render('admin/dashboard', [
            'title' => 'Dashboard',
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers,
            'publishedPages' => $publishedPages,
            'user' => $user
        ]);
    }
}
