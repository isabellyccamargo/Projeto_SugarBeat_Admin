<?php

class View {
    public static function render(string $viewPath, array $data = []) {
        $fullPath = ROOT_PATH . '/app/views/' . $viewPath . '.php';

        if (file_exists($fullPath)) {
            extract($data);
            require $fullPath;
        } else {
            echo "Erro: View '$viewPath' não encontrada.";
        }
    }

    public static function renderWithLayout(string $contentViewPath, string $layoutPath, array $data = []) {
        ob_start();
        
        self::render($contentViewPath, $data);
        $content = ob_get_clean();

        $data['content'] = $content;

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