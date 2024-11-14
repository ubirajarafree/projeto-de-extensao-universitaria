<?php

require_once '../backend/config.php';
require_once 'components/head.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<div class="container mx-auto p-4"><p>Conexão com o banco de dados bem-sucedida!</p></div>';
} catch (PDOException $e) {
    echo '<div class="container mx-auto p-4"><p>Erro na conexão com o banco de dados:</p><p>' . $e->getMessage() . '</p></div>';
}

require_once 'components/footer.php';
