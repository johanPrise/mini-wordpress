<?php ob_start(); ?>

<div class="admin-header">
    <h1>Modifier la page</h1>
    <a href="/admin/pages" class="btn">&larr; Retour</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="/admin/pages/<?= $page['id'] ?>/update" method="POST" class="admin-form">
    <div class="form-group">
        <label for="title">Titre *</label>
        <input type="text" id="title" name="title" required value="<?= htmlspecialchars($page['title']) ?>">
    </div>

    <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text" id="slug" value="<?= htmlspecialchars($page['slug']) ?>" disabled>
        <small>Le slug sera automatiquement mis à jour si vous changez le titre.</small>
    </div>

    <div class="form-group">
        <label for="content">Contenu</label>
        <textarea id="content" name="content" rows="15"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="status">Statut</label>
            <select id="status" name="status">
                <option value="draft" <?= $page['status'] === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                <option value="published" <?= $page['status'] === 'published' ? 'selected' : '' ?>>Publié</option>
            </select>
        </div>

        <div class="form-group">
            <label for="menu_order">Ordre dans le menu</label>
            <input type="number" id="menu_order" name="menu_order" value="<?= $page['menu_order'] ?? 0 ?>" min="0">
        </div>
    </div>

    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="in_menu" value="1" <?= $page['in_menu'] ? 'checked' : '' ?>>
            Afficher dans le menu
        </label>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/admin/pages" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . '/../../layouts/admin.php'; ?>
