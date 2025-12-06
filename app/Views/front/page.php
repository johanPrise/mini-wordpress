<?php ob_start(); ?>

<article class="page-content">
    <header class="page-header">
        <h1><?= htmlspecialchars($page['title']) ?></h1>
        <div class="page-meta">
            <span class="date">Publié le <?= date('d/m/Y', strtotime($page['created_at'])) ?></span>
        </div>
    </header>

    <div class="page-body">
        <?= nl2br(htmlspecialchars($page['content'] ?? '')) ?>
    </div>

    <footer class="page-footer">
        <a href="/" class="btn">&larr; Retour à l'accueil</a>
    </footer>
</article>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . '/../layouts/main.php'; ?>
