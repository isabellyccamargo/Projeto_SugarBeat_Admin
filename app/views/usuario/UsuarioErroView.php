<style>
/* ⚠️ Este CSS deve ser adicionado para centralização vertical. */
.full-height-center {
    min-height: 80vh; /* Usa 80% da altura da viewport para forçar a centralização */
    display: flex;
    flex-direction: column; /* Coloca os itens um abaixo do outro */
    align-items: center; /* Centraliza horizontalmente */
    justify-content: center; /* Centraliza verticalmente */
    text-align: center;
    color: #495057; /* Cor de texto padrão, ajuste se o fundo for escuro */
}

.error-title {
    color: #dc3545; /* Vermelho forte para o título de erro */
    font-size: 2.5rem;
    margin-bottom: 20px;
    font-weight: 600;
}

.error-img {
    max-height: 400px; /* Limita o tamanho da imagem */
    margin-top: 20px;
    margin-bottom: 30px;
}
</style>

<div class="full-height-center">
    
    <h2 class="error-title">
        <i class="fas fa-exclamation-triangle"></i> ACESSO NEGADO
    </h2>
    
    <?php 
    // Captura e limpa a mensagem de alerta da sessão para exibição
    $alert = $_SESSION['alert_message'] ?? null;
    if ($alert) {
        unset($_SESSION['alert_message']); 
    }
    ?>

    <?php if ($alert): ?>
        <div class="alert alert-danger" role="alert" style="max-width: 500px;">
            <p><strong><?php echo htmlspecialchars($alert['title']); ?></strong></p>
            <p><?php echo htmlspecialchars($alert['text']); ?></p>
        </div>
    <?php else: ?>
        <p style="font-size: 1.2rem; color: #6c757d;">
            Você não tem permissão de administrador para acessar esta área.
        </p>
    <?php endif; ?>

    <p>
        <img src="../fotos/erro.png" alt="Erro de Acesso"
        class="img-fluid error-img">
    </p>
    
    <p>
        <a href="/sugarbeat_admin/dashboard" class="btn btn-danger btn-lg">
            <i class="fas fa-home"></i> Voltar à Página Inicial
        </a>
    </p>
</div>