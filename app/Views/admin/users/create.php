<?php ob_start(); ?>

<div class="admin-header">
    <h1>Créer un utilisateur</h1>
    <a href="/admin/users" class="btn">&larr; Retour</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="/admin/users/store" method="POST" class="admin-form">
    <div class="form-group">
        <label for="firstname">Prénom *</label>
        <input type="text" id="firstname" name="firstname" required placeholder="Prénom">
    </div>

    <div class="form-group">
        <label for="lastname">Nom *</label>
        <input type="text" id="lastname" name="lastname" required placeholder="Nom">
    </div>

    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required placeholder="email@exemple.com">
    </div>

    <div class="form-group">
        <label for="password">Mot de passe *</label>
        <input type="password" id="password" name="password" required placeholder="Mot de passe">
    </div>

    <div class="form-group">
        <label for="role">Rôle</label>
        <select id="role" name="role">
            <option value="user">Utilisateur</option>
            <option value="editor">Éditeur</option>
            <option value="admin">Administrateur</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
        <a href="/admin/users" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . '/../../layouts/admin.php'; ?>
