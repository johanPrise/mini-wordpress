<?php ob_start(); ?>

<div class="admin-header">
    <h1>Dashboard</h1>
</div>

<div class="dashboard-welcome">
    <p>Bienvenue, <strong><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></strong> !</p>
    <p class="role-badge">Rôle : <span class="badge"><?= ucfirst($user['role']) ?></span></p>
</div>

<div class="dashboard-stats">
    <?php if (in_array($user['role'], ['admin', 'editor'])): ?>
    <div class="stat-card">
        <div class="stat-number"><?= $totalPages ?></div>
        <div class="stat-label">Pages totales</div>
        <a href="/admin/pages" class="stat-link">Gérer les pages</a>
    </div>

    <div class="stat-card">
        <div class="stat-number"><?= $publishedPages ?></div>
        <div class="stat-label">Pages publiées</div>
    </div>
    <?php endif; ?>

    <?php if ($user['role'] === 'admin' && $totalUsers !== null): ?>
    <div class="stat-card">
        <div class="stat-number"><?= $totalUsers ?></div>
        <div class="stat-label">Utilisateurs</div>
        <a href="/admin/users" class="stat-link">Gérer les utilisateurs</a>
    </div>
    <?php endif; ?>
</div>

<div class="dashboard-actions">
    <h2>Actions rapides</h2>
    <div class="quick-actions">
        <?php if (in_array($user['role'], ['admin', 'editor'])): ?>
        <a href="/admin/pages/create" class="btn btn-primary">+ Nouvelle page</a>
        <?php endif; ?>

        <?php if ($user['role'] === 'admin'): ?>
        <a href="/admin/users/create" class="btn btn-secondary">+ Nouvel utilisateur</a>
        <?php endif; ?>

        <a href="/" target="_blank" class="btn">Voir le site</a>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . '/../layouts/admin.php'; ?>

