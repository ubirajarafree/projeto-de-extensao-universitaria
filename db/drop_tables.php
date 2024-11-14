<?php

require_once '../backend/config.php';
require_once 'components/head.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tablesQuery = $pdo->query("SHOW TABLES");
    $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($tables)) {

        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS $table");
            echo '<div class="container mx-auto p-4"><p>Tabela ' . $table . ' removida com sucesso.</p></div>';
        }

        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    } else {
        echo '<div class="container mx-auto p-4"><p>Nenhuma tabela encontrada no banco de dados.</p></div>';
    }

} catch (PDOException $e) {
    echo '<div class="container mx-auto p-4"><p>Erro ao remover as tabelas: ' . $e->getMessage() . '</p></div>';
}

require_once 'components/footer.php';
