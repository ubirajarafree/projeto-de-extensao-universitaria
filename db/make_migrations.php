<?php

require_once '../backend/config.php';
require_once 'components/head.php';

function getMigrationFiles($directory)
{
    $files = glob($directory . '/*.sql');
    if (empty($files)) {
        return [];
    }

    return $files;
}

$directory = __DIR__ . '/migrations';
$migrationFiles = getMigrationFiles($directory);

if (!empty($migrationFiles)) {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach ($migrationFiles as $file) {
            $query = file_get_contents($file);
            $script = $pdo->prepare($query);
            if ($script->execute()) {
                echo '<div class="container mx-auto p-4"><p>Arquivo ' . basename($file) . ' executado com sucesso.</p></div>';
            }
        }
    } catch (PDOException $e) {
        echo '<div class="container mx-auto p-4"><p>Erro na migração: ' . $e->getMessage() . '</p></div>';
    }
} else {
    echo '<div class="container mx-auto p-4"><p>Nenhum arquivo de migração encontrado.</p></div>';
}

require_once 'components/footer.php';
