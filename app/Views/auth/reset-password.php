<?php 
$title = 'Réinitialiser le mot de passe';
ob_start(); 
?>

<div class="auth-container">
    <h1>Nouveau mot de passe</h1>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" id="resetForm" novalidate>
        <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
        
        <div class="form-group">
            <label for="password">Nouveau mot de passe <span class="required">*</span></label>
            <input 
                type="password" 
                id="password"
                name="password" 
                placeholder="Minimum 8 caractères"
                required
                minlength="8"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
            >
            <span class="error-message"></span>
            <small class="password-hint">Minimum 8 caractères, une majuscule, une minuscule et un chiffre</small>
        </div>
        
        <div class="form-group">
            <label for="password_confirm">Confirmer le mot de passe <span class="required">*</span></label>
            <input 
                type="password" 
                id="password_confirm"
                name="password_confirm" 
                placeholder="Confirmez votre mot de passe"
                required
            >
            <span class="error-message"></span>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Modifier le mot de passe</button>
    </form>
    
    <p class="auth-link">
        <a href="/login">Retour à la connexion</a>
    </p>
</div>

<script>
document.getElementById('resetForm').addEventListener('submit', function(e) {
    let isValid = true;
    const form = this;
    
    form.querySelectorAll('.form-group').forEach(group => {
        group.classList.remove('has-error');
        const errorMsg = group.querySelector('.error-message');
        if (errorMsg) errorMsg.textContent = '';
    });
    
    const password = form.querySelector('#password');
    const passwordConfirm = form.querySelector('#password_confirm');
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    
    if (!passwordRegex.test(password.value)) {
        showError(password, 'Le mot de passe ne respecte pas les critères requis');
        isValid = false;
    }
    
    if (password.value !== passwordConfirm.value) {
        showError(passwordConfirm, 'Les mots de passe ne correspondent pas');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});

function showError(input, message) {
    const formGroup = input.closest('.form-group');
    formGroup.classList.add('has-error');
    formGroup.querySelector('.error-message').textContent = message;
}
</script>

<?php 
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
