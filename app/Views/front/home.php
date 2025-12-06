<?php ob_start(); ?>

<section class="hero">
    <h1>Bienvenue sur Mini WordPress</h1>
    <p>Un CMS simple et léger pour créer votre site web.</p>
</section>

<?php if (!empty($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<section class="pages-list">
    <h2>Nos pages</h2>

    <?php if (!empty($pages)): ?>
        <div class="pages-grid">
            <?php foreach ($pages as $page): ?>
                <article class="page-card">
                    <h3><a href="/<?= htmlspecialchars($page['slug']) ?>"><?= htmlspecialchars($page['title']) ?></a></h3>
                    <p><?= htmlspecialchars(substr(strip_tags($page['content'] ?? ''), 0, 150)) ?>...</p>
                    <a href="/<?= htmlspecialchars($page['slug']) ?>" class="btn">Lire la suite</a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune page publiée pour le moment.</p>
    <?php endif; ?>
</section>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . '/../layouts/main.php'; ?>
