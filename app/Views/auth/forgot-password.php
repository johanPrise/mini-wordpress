<h2>Mot de passe oublié</h2>

<?php if (!empty($error)): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color:green"><?= $success ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email :</label>
    <input type="email" name="email" required>
    <button type="submit">Envoyer le lien</button>
</form>
