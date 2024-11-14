<?php

require_once '../backend/config.php';
require_once 'components/head.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tablesQuery = $pdo->query("SHOW TABLES");
    $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo '<div class="container mx-auto p-4"><p>Não há tabelas no banco de dados.</p></div>';
    } else {
        echo '<div class="container mx-auto p-4">';
        echo '<table class="min-w-full bg-white border border-gray-200">';
        echo '<thead><tr><th class="py-2 px-4 border-b">Tabela</th><th class="py-2 px-4 border-b">Coluna</th><th class="py-2 px-4 border-b">Tipo</th></tr></thead>';
        echo '<tbody>';

        foreach ($tables as $table) {
            echo "<tr><td class='py-2 px-4 border-b' colspan='3'>Tabela: $table</td></tr>";

            $columnsQuery = $pdo->query("SHOW COLUMNS FROM $table");
            $columns = $columnsQuery->fetchAll(PDO::FETCH_ASSOC);

            foreach ($columns as $column) {
                echo "<tr><td class='py-2 px-4 border-b'></td><td class='py-2 px-4 border-b'>{$column['Field']}</td><td class='py-2 px-4 border-b'>{$column['Type']}</td></tr>";
            }
        }

        echo '</tbody></table></div>';
    }
} catch (PDOException $e) {
    echo '<div class="container mx-auto p-4"><p>Erro ao obter a estrutura do banco de dados: ' . $e->getMessage() . '</p></div>';
}

require_once 'components/footer.php';
