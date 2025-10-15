<?php

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SugarBeat Admin - Login</title>
     <link rel="stylesheet" href="/sugarbeat_admin/assets/css/login.css"> 
    
    </head>
<body>

    <div class="login-container">
        
        <div class="login-box">
            <div class="login-circle"></div> 
            
            <h2 class="login-title">Login</h2>
            
            <form class="login-form" action="/sugarbeat_admin/login" method="POST">
                
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        value="<?php echo htmlspecialchars($email ?? ''); ?>"
                    >
                </div>
                
                <div class="input-group">
                    <label for="password">Senha</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                    >
                </div>
                
                <div class="show-password-container">
                    <input type="checkbox" id="show-password">
                    <label for="show-password" class="show-password-label">Mostrar Senha</label>
                </div>
                
                <button type="submit" class="access-button">Acessar</button>
            </form>
        </div>

    </div>

    <script>
        // Lógica simples para mostrar/ocultar senha
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const showPasswordCheckbox = document.getElementById('show-password');

            showPasswordCheckbox.addEventListener('change', function() {
                passwordInput.type = this.checked ? 'text' : 'password';
            });
        });
    </script>

</body>
</html>