<?php 
$title = 'Connexion';
ob_start(); 
?>

<div class="auth-container">
    <h1>Connexion</h1>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <form method="post" id="loginForm" novalidate>
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
        
        <div class="form-group">
            <label for="password">Mot de passe <span class="required">*</span></label>
            <input 
                type="password" 
                id="password"
                name="password" 
                placeholder="Votre mot de passe"
                required
            >
            <span class="error-message"></span>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
    </form>
    
    <p class="auth-link">
        <a href="/forgot-password">Mot de passe oublié ?</a>
    </p>
    
    <p class="auth-link">
        Pas encore inscrit ? <a href="/register">Créer un compte</a>
    </p>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    let isValid = true;
    const form = this;
    
    // Reset errors
    form.querySelectorAll('.form-group').forEach(group => {
        group.classList.remove('has-error');
        group.querySelector('.error-message').textContent = '';
    });
    
    // Validate email
    const email = form.querySelector('#email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim()) {
        showError(email, 'L\'email est requis');
        isValid = false;
    } else if (!emailRegex.test(email.value)) {
        showError(email, 'Veuillez entrer une adresse email valide');
        isValid = false;
    }
    
    // Validate password
    const password = form.querySelector('#password');
    if (!password.value) {
        showError(password, 'Le mot de passe est requis');
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
