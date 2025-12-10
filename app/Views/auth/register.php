<?php 
$title = 'Inscription';
ob_start(); 
?>

<div class="auth-container">
    <h1>Inscription</h1>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <form method="post" id="registerForm" novalidate>
        <div class="form-group">
            <label for="firstname">Prénom <span class="required">*</span></label>
            <input 
                type="text" 
                id="firstname"
                name="firstname" 
                placeholder="Votre prénom"
                required
                minlength="2"
                maxlength="50"
                value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>"
            >
            <span class="error-message"></span>
        </div>
        
        <div class="form-group">
            <label for="lastname">Nom <span class="required">*</span></label>
            <input 
                type="text" 
                id="lastname"
                name="lastname" 
                placeholder="Votre nom"
                required
                minlength="2"
                maxlength="50"
                value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>"
            >
            <span class="error-message"></span>
        </div>
        
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
                placeholder="Minimum 8 caractères"
                required
                minlength="8"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                title="Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre"
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
        
        <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
    </form>
    
    <p class="auth-link">
        Déjà inscrit ? <a href="/login">Se connecter</a>
    </p>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let isValid = true;
    const form = this;
    
    // Reset errors
    form.querySelectorAll('.form-group').forEach(group => {
        group.classList.remove('has-error');
        group.querySelector('.error-message').textContent = '';
    });
    
    // Validate firstname
    const firstname = form.querySelector('#firstname');
    if (!firstname.value.trim() || firstname.value.trim().length < 2) {
        showError(firstname, 'Le prénom doit contenir au moins 2 caractères');
        isValid = false;
    }
    
    // Validate lastname
    const lastname = form.querySelector('#lastname');
    if (!lastname.value.trim() || lastname.value.trim().length < 2) {
        showError(lastname, 'Le nom doit contenir au moins 2 caractères');
        isValid = false;
    }
    
    // Validate email
    const email = form.querySelector('#email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        showError(email, 'Veuillez entrer une adresse email valide');
        isValid = false;
    }
    
    // Validate password
    const password = form.querySelector('#password');
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!passwordRegex.test(password.value)) {
        showError(password, 'Le mot de passe ne respecte pas les critères requis');
        isValid = false;
    }
    
    // Validate password confirmation
    const passwordConfirm = form.querySelector('#password_confirm');
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

// Real-time validation feedback
document.querySelectorAll('#registerForm input').forEach(input => {
    input.addEventListener('blur', function() {
        const formGroup = this.closest('.form-group');
        if (this.checkValidity()) {
            formGroup.classList.remove('has-error');
            formGroup.classList.add('is-valid');
        }
    });
});
</script>

<?php 
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
