<h1>Mot de passe oublié</h1>

<?php if (!empty($error)): ?>
<p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Votre email"><br>
    <button>Envoyer</button>
</form>
