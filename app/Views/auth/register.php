<h1>Inscription</h1>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

<form method="post">
    <input name="firstname" placeholder="Prénom"><br>
    <input name="lastname" placeholder="Nom"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Mot de passe"><br>
    <button>S'inscrire</button>
</form>
