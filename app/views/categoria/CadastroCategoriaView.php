<h2>Cadastro de Nova Categoria</h2>
<p>Preencha os dados da categoria abaixo.</p>

<form action="/sugarbeat/categoria/cadastro" method="POST" style="max-width: 400px; margin-top: 20px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
    <div style="margin-bottom: 15px;">
        <label for="nome" style="display: block; margin-bottom: 5px; font-weight: bold; color: #4a5568;">Nome da Categoria:</label>
        <input type="text" id="nome" name="nome" required 
               style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; box-sizing: border-box;">
    </div>
    
    <button type="submit" 
            style="width: 100%; padding: 10px; background-color: #48bb78; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;">
        Salvar Categoria
    </button>
    
    <a href="/sugarbeat/categoria" 
       style="display: block; text-align: center; margin-top: 10px; color: #4a5568; text-decoration: none;">
       Voltar para a Listagem
    </a>
</form>