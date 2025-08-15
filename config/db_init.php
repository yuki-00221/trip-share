<?php
    require_once __DIR__ . '/db_connect.php';

    $sql = 'CREATE TABLE IF NOT EXISTS users'
          .'('
          .'id INT AUTO_INCREMENT PRIMARY KEY,'
          .'username VARCHAR(50) NOT NULL UNIQUE,'
          .'password VARCHAR(255) NOT NULL,'
          .'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
          .');';
    $stmt = $pdo->query($sql);
    echo 'created table "users"'
?>