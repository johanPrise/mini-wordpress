<h1>Connexion</h1>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    <input name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Mot de passe"><br>
    <button>Connexion</button>
</form>
