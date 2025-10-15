<?php

class View {
    /**
     * Carrega e renderiza um arquivo de view.
     * @param string $viewPath O caminho da view a partir de app/view (ex: 'produtos/ListagemProdutoView').
     * @param array $data Dados a serem passados para a view.
     */
    public static function render(string $viewPath, array $data = []) {
        $fullPath = ROOT_PATH . '/app/views/' . $viewPath . '.php';

        if (file_exists($fullPath)) {
            // Extrai os dados, tornando as chaves do array variáveis na view
            extract($data);

            // Inclui o arquivo de view
            require $fullPath;
        } else {
            // Lidar com erro se a view não existir
            echo "Erro: View '$viewPath' não encontrada.";
        }
    }

    /**
     * Renderiza o conteúdo de uma view dentro do layout principal.
     *
     * Este método foi ajustado para permitir que $layoutPath seja 'core/AppLayout'
     * ou qualquer outro caminho fora de 'app/view/' onde layouts customizados podem residir.
     *
     * @param string $contentViewPath O caminho da view de conteúdo (ex: 'dashboard/DashboardView').
     * @param string $layoutPath O caminho do layout (ex: 'core/AppLayout' ou 'layout/MeuLayout').
     * @param array $data Dados a serem passados para a view e o layout.
     */
    public static function renderWithLayout(string $contentViewPath, string $layoutPath, array $data = []) {
        // 1. Inicia o buffer de saída
        ob_start();
        
        // 2. Renderiza a view de conteúdo e armazena o HTML em $content
        // Assume que as views de conteúdo SEMPRE estão em app/view/
        self::render($contentViewPath, $data);
        $content = ob_get_clean();

        // 3. Renderiza o layout principal, que irá incluir $content
        $data['content'] = $content;

        // Determina o caminho COMPLETO para o arquivo de Layout.
        // Se o caminho do layout contiver um separador de diretório, ele é tratado como um caminho absoluto (a partir de app/).
        // Ex: 'core/AppLayout' -> ROOT_PATH . /app/core/AppLayout.php
        // Ex: 'layout/main' -> ROOT_PATH . /app/view/layout/main.php
        $isCoreLayout = strpos($layoutPath, 'core/') === 0 || strpos($layoutPath, 'config/') === 0;

        if ($isCoreLayout) {
            // Se o layout for referenciado como 'core/...' ou 'config/...', procuramos no diretório 'app/'.
            $layoutFullPath = ROOT_PATH . '/app/' . $layoutPath . '.php';
        } else {
            // Caso contrário, usamos a lógica padrão para views de layout (dentro de app/view/)
            $layoutFullPath = ROOT_PATH . '/app/views/' . $layoutPath . '.php';
        }


        if (file_exists($layoutFullPath)) {
            extract($data);
            require $layoutFullPath;
        } else {
            // Lidar com erro se o layout não existir
            echo "Erro: View ou Layout '$layoutPath' não encontrada. Caminho tentado: $layoutFullPath";
        }
    }
}