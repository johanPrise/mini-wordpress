<?php 
$title = 'Mot de passe oublié';
ob_start(); 
?>

<div class="auth-container">
    <h1>Mot de passe oublié</h1>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <p style="text-align: center; color: #666; margin-bottom: 1.5rem;">
        Entrez votre adresse email pour recevoir un lien de réinitialisation.
    </p>
    
    <form method="POST" id="forgotForm" novalidate>
        <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input 
                type="email" 
                id="email"
                name="email" 
                placeholder="exemple@email.com"
                required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            >
            <span class="error-message"></span>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Envoyer le lien</button>
    </form>
    
    <p class="auth-link">
        <a href="/login">Retour à la connexion</a>
    </p>
</div>

<script>
document.getElementById('forgotForm').addEventListener('submit', function(e) {
    const email = this.querySelector('#email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const formGroup = email.closest('.form-group');
    
    formGroup.classList.remove('has-error');
    formGroup.querySelector('.error-message').textContent = '';
    
    if (!email.value.trim()) {
        e.preventDefault();
        formGroup.classList.add('has-error');
        formGroup.querySelector('.error-message').textContent = 'L\'email est requis';
    } else if (!emailRegex.test(email.value)) {
        e.preventDefault();
        formGroup.classList.add('has-error');
        formGroup.querySelector('.error-message').textContent = 'Veuillez entrer une adresse email valide';
    }
});
</script>

<?php 
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
