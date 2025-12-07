<?php ob_start(); ?>

<div class="admin-header">
    <h1>Modifier l'utilisateur</h1>
    <a href="/admin/users" class="btn">&larr; Retour</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="/admin/users/<?= $user['id'] ?>/update" method="POST" class="admin-form">
    <div class="form-group">
        <label for="username">Nom d'utilisateur *</label>
        <input type="text" id="username" name="username" required value="<?= htmlspecialchars($user['username']) ?>">
    </div>

    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
    </div>

    <div class="form-group">
        <label for="password">Nouveau mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Laisser vide pour ne pas changer">
        <small>Laissez vide pour conserver le mot de passe actuel.</small>
    </div>

    <div class="form-group">
        <label for="role">Rôle</label>
        <select id="role" name="role">
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
            <option value="editor" <?= $user['role'] === 'editor' ? 'selected' : '' ?>>Éditeur</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        </select>
    </div>

    <div class="form-info">
        <p><strong>Date d'inscription :</strong> <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></p>
        <p><strong>Email vérifié :</strong> <?= $user['email_verified_at'] ? 'Oui (' . date('d/m/Y', strtotime($user['email_verified_at'])) . ')' : 'Non' ?></p>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/admin/users" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . '/../../layouts/admin.php'; ?>
