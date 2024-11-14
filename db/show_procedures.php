<?php

require_once '../backend/config.php';
require_once 'components/head.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $showProcedureStatusQuery = $pdo->query("SHOW PROCEDURE STATUS WHERE Db = '" . DB_NAME . "'");
    $procedures = $showProcedureStatusQuery->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($procedures)) {
        echo '<div class="container mx-auto p-4"><p>Procedimentos armazenados no banco de dados:</p></div>';
        foreach ($procedures as $procedure) {
            echo '<div class="container mx-auto p-4"><p>Nome: ' . $procedure['Name'] . ' - Criado em: ' . $procedure['Created'] . '</p></div>';
        }
    } else {
        echo '<div class="container mx-auto p-4"><p>Nenhum procedimento armazenado encontrado no banco de dados.</p></div>';
    }

} catch (PDOException $e) {
    echo '<div class="container mx-auto p-4"><p>Erro ao obter a estrutura do banco de dados: ' . $e->getMessage() . '</p></div>';
}

require_once 'components/footer.php';
