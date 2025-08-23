<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$title = trim($_POST['title'] ?? '');
$body = trim($_POST['body'] ?? '');
$prefecture = $_POST['prefecture'] ?? '';
$travel_date = $_POST['travel_date'] ?? '';
$post_id = $_POST['id'] ?? null;

if ($title === '' || $body === '' || $prefecture === '' || $travel_date === '') {
    $_SESSION['error'] = 'すべての必須項目を入力してください';
    $_SESSION['old'] = $_POST;
    $redirect = $post_id ? "post_form.php?id=$post_id" : 'post_form.php';
    header("Location: $redirect");
    exit;
}

$image_path = null;
require_once __DIR__ . '/../config/db_connect.php';
if ($post_id) {
    $stmt = $pdo->prepare('SELECT image_path
                           FROM posts
                           WHERE id = :id AND user_id = :user_id
                           LIMIT 1');
    $stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    $existing_image = $post['image_path'] ?? null;

    if (!empty($_POST['delete_image']) && $existing_image) {
        $file_path = __DIR__ . '/../' . $existing_image;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $existing_image = null;
    }
}

if (!empty($_FILES['image']['name'])) {
    $upload_dir = __DIR__ . '/assets/uploads/';
    $filename = time() . '_' . basename($_FILES['image']['name']);
    $target = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $image_path = 'assets/uploads/' . $filename;
    }
}

if ($post_id) {
    $final_image = $image_path ?? $existing_image;
    $stmt = $pdo->prepare('UPDATE posts 
                           SET title = :title, body = :body,
                               prefecture = :prefecture, travel_date = :travel_date,
                               image_path = :image_path 
                           WHERE id = :id AND user_id = :user_id');
    $stmt->bindValue(':image_path', $final_image, PDO::PARAM_STR);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':body', $body, PDO::PARAM_STR);
    $stmt->bindValue(':prefecture', $prefecture, PDO::PARAM_STR);
    $stmt->bindValue(':travel_date', $travel_date, PDO::PARAM_STR);
    $stmt->bindValue(':image_path', $final_image, PDO::PARAM_STR);
    $stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $pdo->prepare('INSERT INTO posts (user_id, title, body, prefecture, travel_date, image_path, created_at) 
                           VALUES (:user_id, :title, :body, :prefecture, :travel_date, :image_path, NOW())');
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':body', $body, PDO::PARAM_STR);
    $stmt->bindValue(':prefecture', $prefecture, PDO::PARAM_STR);
    $stmt->bindValue(':travel_date', $travel_date, PDO::PARAM_STR);
    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $stmt->execute();
}

header('Location: index.php');
exit;
