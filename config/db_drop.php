<?php
    require_once __DIR__ . '/db_connect.php';

    $sql = 'DROP TABLE users';
    $stmt = $pdo->query($sql);
    echo 'deleted table "users"'
?>