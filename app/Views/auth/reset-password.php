<h2>Réinitialiser votre mot de passe</h2>

<?php if (!empty($error)): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="email" value="<?= $email ?>">
    <input type="hidden" name="token" value="<?= $token ?>">

    <label>Nouveau mot de passe :</label>
    <input type="password" name="password" required>

    <button type="submit">Modifier</button>
</form>
