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
    echo 'created table "users"</br>';

    $sql = 'CREATE TABLE IF NOT EXISTS posts'
          .'('
          .'id INT AUTO_INCREMENT PRIMARY KEY,'
          .'user_id INT NOT NULL,'
          .'title VARCHAR(255) NOT NULL,'
          .'body TEXT NOT NULL,'
          .'prefecture VARCHAR(50) NOT NULL,'
          .'travel_date DATE NOT NULL,'
          .' image_path VARCHAR(255),'
          .'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,'
          .'FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE'
          .');';
    $stmt = $pdo->query($sql);
    echo 'created table "posts"</br>'
?>