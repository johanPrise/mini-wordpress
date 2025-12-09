<h1>Nouveau mot de passe</h1>

<form method="POST">
    <input type="hidden" name="token" value="<?= $token ?>">
    <input type="password" name="pwd" placeholder="Nouveau mot de passe"><br>
    <button>Changer</button>
</form>
