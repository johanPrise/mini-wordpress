<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Mini WordPress') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="/">Mini WordPress</a>
            </div>
            <ul class="menu">
                <li><a href="/">Accueil</a></li>
                <?php if (!empty($menuPages)): ?>
                    <?php foreach ($menuPages as $menuPage): ?>
                        <li><a href="/<?= htmlspecialchars($menuPage['slug']) ?>"><?= htmlspecialchars($menuPage['title']) ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="auth-links">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/admin">Dashboard</a>
                    <a href="/logout">Déconnexion</a>
                <?php else: ?>
                    <a href="/login">Connexion</a>
                    <a href="/register">Inscription</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <?= $content ?? '' ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Mini WordPress. Tous droits réservés.</p>
    </footer>

    <script src="/assets/js/app.js"></script>
</body>
</html>
