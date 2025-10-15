<h2>Cadastro de Novo Produto</h2>
<p>Preencha os detalhes do produto abaixo.</p>

<form action="/sugarbeat/produto/cadastro" method="POST" style="max-width: 500px; margin-top: 20px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
    <div style="margin-bottom: 15px;">
        <label for="nome" style="display: block; margin-bottom: 5px; font-weight: bold; color: #4a5568;">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" required 
               style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; box-sizing: border-box;">
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="preco" style="display: block; margin-bottom: 5px; font-weight: bold; color: #4a5568;">Preço (R$):</label>
        <input type="number" id="preco" name="preco" required step="0.01" 
               style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; box-sizing: border-box;">
    </div>

    <div style="margin-bottom: 20px;">
        <label for="categoria" style="display: block; margin-bottom: 5px; font-weight: bold; color: #4a5568;">Categoria:</label>
        <select id="categoria" name="categoria" required
                style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; box-sizing: border-box;">
            <option value="">Selecione...</option>
            <option value="1">Açúcares</option>
            <option value="2">Derivados</option>
        </select>
    </div>
    
    <button type="submit" 
            style="width: 100%; padding: 10px; background-color: #48bb78; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;">
        Salvar Produto
    </button>
    
    <a href="/sugarbeat/produto" 
       style="display: block; text-align: center; margin-top: 10px; color: #4a5568; text-decoration: none;">
       Voltar para a Listagem
    </a>
</form>
