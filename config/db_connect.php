<?php
    $config = require __DIR__ . '/config.php';

    $host = $config['db_host'];
    $db = $config['db_name'];
    $user = $config['db_user'];
    $pass = $config['db_pass'];
    $charset = $config['charset'];

    $dsn = "mysql:dbname=$db;host=$host;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch(PDOException $e) {
        echo 'DB接続失敗: '. $e->getMessage();
        exit;
    }
?>