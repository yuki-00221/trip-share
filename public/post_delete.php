<?php
    session_start();
    require_once __DIR__ . '/../config/db_connect.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header('Location: index.php');
        exit;
    }

    $post_id = (int)$_GET['id'];

    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ? AND user_id = ? LIMIT 1');
    $stmt->execute([$post_id, $_SESSION['user_id']]);
    $post = $stmt->fetch();

    if (!$post) {
        echo '削除できる投稿がありません。';
        exit;
    }

    if (!empty($post['image_path']) && file_exists(__DIR__ . '/' . $post['image_path'])) {
        unlink(__DIR__ . '/' . $post['image_path']);
    }

    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
    $stmt->execute([$post_id, $_SESSION['user_id']]);

    header('Location: index.php');
    exit;
?>