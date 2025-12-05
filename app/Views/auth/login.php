<?php
/**
 * =====================================================================
 * 🔐 VUE LOGIN - Formulaire de connexion
 * =====================================================================
 * 
 * Cette vue affiche le formulaire de connexion.
 * 
 * 📚 COMMENT ÇA MARCHE ?
 *    1. AuthController->showLogin() inclut ce fichier
 *    2. L'utilisateur remplit le formulaire
 *    3. Le formulaire envoie les données en POST vers /login/submit
 *    4. AuthController->login() traite la connexion
 * 
 * 💡 SÉCURITÉ :
 *    - method="POST" : les données ne sont pas visibles dans l'URL
 *    - htmlspecialchars() : protège contre les attaques XSS
 * =====================================================================
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - <?= APP_NAME ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            text-align: center;
            color: #999;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            width: 100%;
            padding: 14px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #5a67d8;
        }
        .error {
            background: #fee;
            color: #c00;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .links {
            text-align: center;
            margin-top: 1.5rem;
        }
        .links a {
            color: #667eea;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>🔐 Connexion</h1>
        <p class="subtitle">Accédez à votre compte</p>
        
        <?php
        /**
         * 📚 AFFICHAGE DES ERREURS
         * 
         * Si le controller a stocké une erreur dans la session,
         * on l'affiche ici puis on la supprime.
         * 
         * $_SESSION['error'] est défini dans AuthController->login()
         * quand la connexion échoue.
         */
        if (!empty($_SESSION['error'])): ?>
            <div class="error">
                <!-- 
                    htmlspecialchars() convertit les caractères spéciaux en entités HTML
                    Cela protège contre les attaques XSS (injection de script)
                -->
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); // Supprime l'erreur après affichage ?>
        <?php endif; ?>
        
        <!-- 
            📚 FORMULAIRE HTML
            
            action="/login/submit" : URL où envoyer les données
            method="POST" : méthode HTTP (les données sont dans le corps, pas l'URL)
        -->
        <form action="/login/submit" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <!-- 
                    type="email" : validation automatique du format email
                    required : champ obligatoire
                    name="email" : clé pour récupérer la valeur dans $_POST['email']
                -->
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    placeholder="votre@email.com"
                    autocomplete="email"
                >
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="••••••••"
                    autocomplete="current-password"
                >
            </div>
            
            <button type="submit">Se connecter</button>
        </form>
        
        <div class="links">
            <a href="/forgot-password">Mot de passe oublié ?</a>
            <br><br>
            <span>Pas encore de compte ?</span>
            <a href="/register">S'inscrire</a>
            <br><br>
            <a href="/">← Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
