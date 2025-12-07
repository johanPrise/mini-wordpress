<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Page;

/**
 * PageController - Gestion des pages (CRUD) côté admin
 */
class PageController extends Controller
{
    /**
     * Vérifier si l'utilisateur est connecté (middleware simple)
     */
    private function requireAuth(): void
    {
        if (!Session::get('user')) {
            $this->redirect('/login');
        }
    }

    /**
     * Liste des pages (admin)
     * GET /admin/pages
     */
    public function index(): void
    {
        $this->requireAuth();

        $pages = Page::all();

        $this->render('admin/pages/index', [
            'title' => 'Gestion des pages',
            'pages' => $pages,
            'success' => $this->getFlash('success'),
            'error' => $this->getFlash('error')
        ]);
    }

    /**
     * Afficher le formulaire de création
     * GET /admin/pages/create
     */
    public function create(): void
    {
        $this->requireAuth();

        $this->render('admin/pages/create', [
            'title' => 'Créer une page',
            'error' => $this->getFlash('error')
        ]);
    }

    /**
     * Enregistrer une nouvelle page
     * POST /admin/pages/store
     */
    public function store(): void
    {
        $this->requireAuth();

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $status = $_POST['status'] ?? 'draft';
        $inMenu = isset($_POST['in_menu']) ? 1 : 0;
        $menuOrder = (int) ($_POST['menu_order'] ?? 0);

        // Validation
        if (empty($title)) {
            $this->flash('error', 'Le titre est obligatoire.');
            $this->redirect('/admin/pages/create');
            return;
        }

        // Générer le slug
        $slug = Page::generateSlug($title);

        // Créer la page
        $user = Session::get('user');
        $pageId = Page::create([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'status' => $status,
            'in_menu' => $inMenu,
            'menu_order' => $menuOrder,
            'author_id' => $user['id'] ?? null
        ]);

        if ($pageId) {
            $this->flash('success', 'Page créée avec succès.');
            $this->redirect('/admin/pages');
        } else {
            $this->flash('error', 'Erreur lors de la création de la page.');
            $this->redirect('/admin/pages/create');
        }
    }

    /**
     * Afficher le formulaire d'édition
     * GET /admin/pages/{id}/edit
     */
    public function edit(int $id): void
    {
        $this->requireAuth();

        $page = Page::find($id);

        if (!$page) {
            $this->flash('error', 'Page introuvable.');
            $this->redirect('/admin/pages');
            return;
        }

        $this->render('admin/pages/edit', [
            'title' => 'Modifier la page',
            'page' => $page,
            'error' => $this->getFlash('error')
        ]);
    }

    /**
     * Mettre à jour une page
     * POST /admin/pages/{id}/update
     */
    public function update(int $id): void
    {
        $this->requireAuth();

        $page = Page::find($id);

        if (!$page) {
            $this->flash('error', 'Page introuvable.');
            $this->redirect('/admin/pages');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $status = $_POST['status'] ?? 'draft';
        $inMenu = isset($_POST['in_menu']) ? 1 : 0;
        $menuOrder = (int) ($_POST['menu_order'] ?? 0);

        // Validation
        if (empty($title)) {
            $this->flash('error', 'Le titre est obligatoire.');
            $this->redirect("/admin/pages/$id/edit");
            return;
        }

        // Générer le slug si le titre a changé
        $slug = $page['slug'];
        if ($title !== $page['title']) {
            $slug = Page::generateSlug($title, $id);
        }

        // Mettre à jour
        $updated = Page::update($id, [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'status' => $status,
            'in_menu' => $inMenu,
            'menu_order' => $menuOrder
        ]);

        if ($updated) {
            $this->flash('success', 'Page mise à jour avec succès.');
        } else {
            $this->flash('error', 'Erreur lors de la mise à jour.');
        }

        $this->redirect('/admin/pages');
    }

    /**
     * Supprimer une page
     * POST /admin/pages/{id}/delete
     */
    public function delete(int $id): void
    {
        $this->requireAuth();

        $page = Page::find($id);

        if (!$page) {
            $this->flash('error', 'Page introuvable.');
            $this->redirect('/admin/pages');
            return;
        }

        $deleted = Page::delete($id);

        if ($deleted) {
            $this->flash('success', 'Page supprimée avec succès.');
        } else {
            $this->flash('error', 'Erreur lors de la suppression.');
        }

        $this->redirect('/admin/pages');
    }

    // ========================================
    // FRONT-OFFICE
    // ========================================

    /**
     * Afficher une page par son slug (front-office)
     * GET /{slug}
     */
    public function show(string $slug): void
    {
        $page = Page::findBySlug($slug);

        if (!$page || $page['status'] !== 'published') {
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
