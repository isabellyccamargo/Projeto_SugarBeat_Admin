<h2>Usuários</h2>
<p>Visualize e edite os usuários do sistema.</p>
<div style="margin-top: 15px;">
    <?php if (!empty($usuarios)): ?>
        <ul style="list-style: disc; padding-left: 20px;">
            <?php foreach ($usuarios as $usuario): ?>
                <!-- CORREÇÃO: Adicionado o fechamento da tag PHP (?>) -->
                <li><?= htmlspecialchars($usuario) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhum usuário encontrado.</p>
    <?php endif; ?>
    <a href="/sugarbeat/usuario/cadastro" style="display: inline-block; padding: 8px 15px; background-color: #68d391; border: none; color: white; border-radius: 4px; text-decoration: none; cursor: pointer; margin-top: 10px;">
        Adicionar Usuário
    </a>
</div>
