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

<style> 
    :root {
    --fontfamily: 'Calibri', 'Trebuchet MS', 'Segoe UI', sans-serif;
    --cor-primaria: #3b2500ff; /* Marrom Escuro Original */
    --cor-secundaria: rgb(248, 239, 218); /* Bege Claro Original */
    --cor-terceira: rgb(253, 230, 182); /* Bege um pouco mais escuro Original */

    /* NOVAS CORES PARA INVERSÃO */
    --cor-fundo-login-box: var(--cor-secundaria); /* Era o marrom, agora é o bege claro */
    --cor-texto-login-box: var(--cor-primaria);   /* Era branco, agora é o marrom */
    --cor-input-background: #ffffff;             /* Pode ser branco puro ou um tom de bege/creme */
    --cor-botao-background: var(--cor-primaria); /* Era bege, agora é o marrom */
    --cor-botao-texto: white;                    /* Era marrom, agora é branco */

    --cor-input-border: #d4c29d; 
}

body {
    margin: 0;
    padding: 0;
    font-family: var(--fontfamily);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: url('/sugarbeat_admin/fotos/imglogin1.jpg') no-repeat center center/cover;
    position: relative;
}

/*CAMADA TRANSPARENTE*/
body::before {
    content: "";
    position: absolute;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.7); 
    z-index: 0;
}

/* CONTAINER CENTRAL */
.login-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    position: relative;
    z-index: 1;
}

/*CAIXA DE LOGIN*/
.login-box {
    background-color: var(--cor-fundo-login-box);
    width: 400px;
    padding: 40px 50px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.login-logo {
    width: 60px; 
    height: 60px; 
    background-color: transparent; 
    border: 1px solid var(--cor-primaria); 
    border-radius: 50%;
    margin: 0 auto 10px auto; 
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.login-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/*TÍTULOs */
.login-title {
    color: var(--cor-texto-login-box); 
    font-size: 28px;
    margin-bottom: 30px;
    font-weight: normal;
    font-family: var(--fontfamily);
    
}

.logo-subtitle {
    color: var(--cor-texto-login-box); 
    font-size: 35px;
    margin-bottom: 30px;
    font-weight: normal;
    font-family: var(--fontfamily);
    font-weight: bold;
}

/*CAMPOS*/
.input-group {
    text-align: left;
    margin-bottom: 20px;
     position: relative; 
    text-align: left;
    margin-bottom: 20px;
}

.input-group .icon {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: var(--cor-texto-login-box);
    font-size: 18px;
    z-index: 2; 
}

.input-group label {
    color: var(--cor-texto-login-box); 
    display: block;
    margin-bottom: 5px;
    font-size: 19px;
}

.input-group input {
    width: 100%;
    padding: 10px 10px 10px 45px;
    background-color: var(--cor-input-background);
    border: 1px solid var(--cor-input-border);
    outline: none;
    font-size: 18px;
    color: #000;
    border-radius: 5px;
    box-sizing: border-box; 
}


/*CHECKBOX */
.show-password-container {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-bottom: 25px;
    font-size: 16px;
}

.show-password-container input {
    accent-color: var(--cor-primaria);
    margin-right: 5px;
}

.show-password-label {
    color: var(--cor-texto-login-box);
}

/* BOTÃO */
.access-button {
    background-color: var(--cor-botao-background); 
    border: none;
    padding: 10px 0;
    width: 160px;
    font-size: 18px;
    cursor: pointer;
    color: var(--cor-secundaria); 
    transition: 0.3s;
    border-radius: 5px;
    font-weight: bold;
}

.access-button:hover {
    background-color: #5a3d1d; 
}

/*ERRO*/
.error-message {
    background-color: #f8d7da;
    color: #842029;
    padding: 8px;
    margin-bottom: 15px;
    border-radius: 4px;
    font-size: 16px;
    width: 405px;
    border: 1px solid #f5c2c7; 
}
</style>

<body>

    <div class="login-container">

        <div class="login-box">

            

            <div class="login-logo">
                <img src="/sugarbeat_admin/fotos/imgsite.jpg" alt="Logo da Empresa">
            </div>

            <div class="logo-subtitle">
                Sugar Beat Admin
            </div>

            <h2 class="login-title">Login</h2>

            <form class="login-form" action="/sugarbeat_admin/login" method="POST">

                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="input-group">
                    <i class="fas fa-user icon"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="E-mail"
                        required
                        value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-lock icon"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Senha"
                        required>
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