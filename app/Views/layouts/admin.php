<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin') ?> | Mini WordPress</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2><a href="/admin">Mini WP</a></h2>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="/admin">Dashboard</a></li>
                <li><a href="/admin/pages">Pages</a></li>
                <li><a href="/admin/users">Utilisateurs</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="/" target="_blank">Voir le site</a>
            <a href="/logout">Déconnexion</a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div class="user-info">
                <?php if (isset($_SESSION['user'])): ?>
                    Connecté en tant que <strong><?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?></strong>
                <?php endif; ?>
            </div>
        </header>

        <div class="admin-content">
            <?= $content ?? '' ?>
        </div>
    </main>

    <script src="/assets/js/admin.js"></script>
</body>
</html>
