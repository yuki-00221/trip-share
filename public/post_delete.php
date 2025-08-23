<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$post_id = (int)$_GET['id'];
require_once __DIR__ . '/../config/db_connect.php';
$stmt = $pdo->prepare('SELECT *
                       FROM posts
                       WHERE id = :id AND user_id = :uid
                       LIMIT 1');
$stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
$stmt->bindValue(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch();

if (!$post) {
    echo '削除できる投稿がありません。';
    exit;
}

if (!empty($post['image_path']) && file_exists(__DIR__ . '/' . $post['image_path'])) {
    unlink(__DIR__ . '/' . $post['image_path']);
}

$stmt = $pdo->prepare('DELETE
                       FROM posts 
                       WHERE id = :id AND user_id = :uid');
$stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
$stmt->bindValue(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();

header('Location: index.php');
exit;
