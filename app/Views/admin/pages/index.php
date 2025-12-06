<?php ob_start(); ?>

<div class="admin-header">
    <h1>Gestion des pages</h1>
    <a href="/admin/pages/create" class="btn btn-primary">+ Nouvelle page</a>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Slug</th>
            <th>Statut</th>
            <th>Menu</th>
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($pages)): ?>
            <?php foreach ($pages as $page): ?>
                <tr>
                    <td><?= $page['id'] ?></td>
                    <td><?= htmlspecialchars($page['title']) ?></td>
                    <td><code><?= htmlspecialchars($page['slug']) ?></code></td>
                    <td>
                        <span class="badge badge-<?= $page['status'] === 'published' ? 'success' : 'warning' ?>">
                            <?= $page['status'] === 'published' ? 'Publié' : 'Brouillon' ?>
                        </span>
                    </td>
                    <td><?= $page['in_menu'] ? '✓' : '—' ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($page['created_at'])) ?></td>
                    <td class="actions">
                        <a href="/<?= htmlspecialchars($page['slug']) ?>" class="btn btn-sm" target="_blank">Voir</a>
                        <a href="/admin/pages/<?= $page['id'] ?>/edit" class="btn btn-sm btn-secondary">Modifier</a>
                        <form action="/admin/pages/<?= $page['id'] ?>/delete" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette page ?');">
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">Aucune page trouvée.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . '/../../layouts/admin.php'; ?>
