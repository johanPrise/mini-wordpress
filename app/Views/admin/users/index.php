<?php ob_start(); ?>

<div class="admin-header">
    <h1>Gestion des utilisateurs</h1>
    <a href="/admin/users/create" class="btn btn-primary">+ Nouvel utilisateur</a>
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
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actif</th>
            <th>Date d'inscription</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['firstname']) ?></td>
                    <td><?= htmlspecialchars($user['lastname']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'editor' ? 'warning' : 'secondary') ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td><?= $user['is_active'] ? '✓' : '✗' ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                    <td class="actions">
                        <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn btn-sm btn-secondary">Modifier</a>
                        <?php if ($_SESSION['user']['id'] != $user['id']): ?>
                            <form action="/admin/users/<?= $user['id'] ?>/delete" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">Aucun utilisateur trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . '/../../layouts/admin.php'; ?>
