<?php
/**
 * =====================================================================
 * 🏠 VUE HOME - Page d'accueil du site
 * =====================================================================
 * 
 * Cette vue affiche la page d'accueil publique du site.
 * 
 * 📚 COMMENT ÇA MARCHE ?
 *    1. Le controller HomeController->index() inclut ce fichier
 *    2. Les constantes APP_NAME, APP_VERSION sont disponibles (config/app.php)
 *    3. Les variables passées par le controller sont aussi disponibles
 * 
 * 💡 ASTUCE : On peut utiliser <?= $variable ?> au lieu de <?php echo $variable; ?>
 * =====================================================================
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Accueil</title>
    <style>
        /* 🎨 Styles de base pour la page d'accueil */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .hero {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            margin-top: 4rem;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        .version {
            color: #999;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .description {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }
        .cta {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }
        .cta:hover {
            background: #5a67d8;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        .feature {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .nav {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        .nav a {
            color: #667eea;
            text-decoration: none;
        }
        .nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero">
            <!-- 
                📚 EXPLICATION :
                <?= APP_NAME ?> affiche la valeur de la constante APP_NAME
                définie dans config/app.php
            -->
            <h1>🚀 <?= APP_NAME ?></h1>
            <p class="version">Version <?= APP_VERSION ?></p>
            
            <p class="description">
                Un mini CMS construit pour apprendre PHP de manière progressive.
            </p>
            
            <a href="/login" class="cta">Se connecter</a>
            
            <div class="features">
                <div class="feature">
                    <div class="feature-icon">📚</div>
                    <strong>Apprentissage</strong>
                    <p>Code commenté pour apprendre</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">🏗️</div>
                    <strong>Architecture MVC</strong>
                    <p>Pattern professionnel</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">🔐</div>
                    <strong>Authentification</strong>
                    <p>Système de login complet</p>
                </div>
            </div>
            
            <nav class="nav">
                <a href="/login">Connexion</a>
                <a href="/register">Inscription</a>
                <a href="/admin">Administration</a>
            </nav>
        </div>
    </div>
</body>
</html>
