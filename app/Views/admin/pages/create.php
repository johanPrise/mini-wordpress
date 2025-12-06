<?php ob_start(); ?>

<div class="admin-header">
    <h1>Créer une page</h1>
    <a href="/admin/pages" class="btn">&larr; Retour</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="/admin/pages/store" method="POST" class="admin-form">
    <div class="form-group">
        <label for="title">Titre *</label>
        <input type="text" id="title" name="title" required placeholder="Titre de la page">
    </div>

    <div class="form-group">
        <label for="content">Contenu</label>
        <textarea id="content" name="content" rows="15" placeholder="Contenu de la page..."></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="status">Statut</label>
            <select id="status" name="status">
                <option value="draft">Brouillon</option>
                <option value="published">Publié</option>
            </select>
        </div>

        <div class="form-group">
            <label for="menu_order">Ordre dans le menu</label>
            <input type="number" id="menu_order" name="menu_order" value="0" min="0">
        </div>
    </div>

    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="in_menu" value="1">
            Afficher dans le menu
        </label>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Créer la page</button>
        <a href="/admin/pages" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . '/../../layouts/admin.php'; ?>
