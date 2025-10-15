<h2>Produtos</h2>
<p>Aqui você pode gerenciar os produtos cadastrados.</p>
<div style="margin-top: 15px;">
    <?php if (!empty($listaProdutos)): ?>
        <ul style="list-style: disc; padding-left: 20px;">
            <?php foreach ($listaProdutos as $produto): ?>
                <li><?= htmlspecialchars($produto) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhum produto encontrado.</p>
    <?php endif; ?>
    <a href="/sugarbeat/produto/cadastro" style="display: inline-block; padding: 8px 15px; background-color: #68d391; border: none; color: white; border-radius: 4px; text-decoration: none; cursor: pointer; margin-top: 10px;">
        Adicionar Produto
    </a>
</div>