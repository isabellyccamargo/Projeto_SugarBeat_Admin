<?php

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SugarBeat Admin - Login</title>
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/login.css"> 
    <link rel="icon" type="image/png" href="/sugarbeat_admin/fotos/imgsite.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    
    </head>
<body>

    <div class="login-container">
        
        <div class="login-box">
            <div class="login-logo">
                <img src="/sugarbeat_admin/fotos/imgsite.jpg" alt="Logo da Empresa">
            </div> 
            
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