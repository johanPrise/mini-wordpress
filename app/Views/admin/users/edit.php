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
        <label for="firstname">Prénom *</label>
        <input type="text" id="firstname" name="firstname" required value="<?= htmlspecialchars($user['firstname']) ?>">
    </div>

    <div class="form-group">
        <label for="lastname">Nom *</label>
        <input type="text" id="lastname" name="lastname" required value="<?= htmlspecialchars($user['lastname']) ?>">
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
        <p><strong>Compte actif :</strong> <?= $user['is_active'] ? 'Oui' : 'Non' ?></p>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/admin/users" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . '/../../layouts/admin.php'; ?>
