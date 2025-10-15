<h2>Cadastro de Novo Usuário</h2>
<p>Preencha os dados do novo usuário para acesso ao sistema.</p>

<form action="/sugarbeat/usuario/cadastro" method="POST" style="max-width: 400px; margin-top: 20px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
    <div style="margin-bottom: 15px;">
        <label for="nome" style="display: block; margin-bottom: 5px; font-weight: bold; color: #4a5568;">Nome Completo:</label>
        <input type="text" id="nome" name="nome" required 
               style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; box-sizing: border-box;">
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold; color: #4a5568;">E-mail:</label>
        <input type="email" id="email" name="email" required 
               style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; box-sizing: border-box;">
    </div>

    <div style="margin-bottom: 20px;">
        <label for="senha" style="display: block; margin-bottom: 5px; font-weight: bold; color: #4a5568;">Senha:</label>
        <input type="password" id="senha" name="senha" required 
               style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; box-sizing: border-box;">
    </div>
    
    <button type="submit" 
            style="width: 100%; padding: 10px; background-color: #4c51bf; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;">
        Cadastrar Usuário
    </button>
    
    <a href="/sugarbeat/usuario" 
       style="display: block; text-align: center; margin-top: 10px; color: #4a5568; text-decoration: none;">
       Voltar para a Listagem
    </a>
</form>