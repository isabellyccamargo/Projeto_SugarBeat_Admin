<h2>Categorias</h2>
<p>Aqui você pode gerenciar as categorias de produtos.</p>
<div style="margin-top: 15px;">
    <?php if (!empty($categorias)): ?>
        <ul style="list-style: disc; padding-left: 20px;">
            <?php foreach ($categorias as $categoria): ?>
                <li><?= htmlspecialchars($categoria) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhuma categoria encontrada.</p>
    <?php endif; ?>
    <a href="/sugarbeat_admin/categoria/cadastro" style="display: inline-block; padding: 8px 15px; background-color: #68d391; border: none; color: white; border-radius: 4px; text-decoration: none; cursor: pointer; margin-top: 10px;">
        Adicionar Categoria
    </a>
</div>