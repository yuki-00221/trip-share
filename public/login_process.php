<?php
session_start();

require_once '../config/db_connect.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare('SELECT *
                       FROM users
                       WHERE username = :username
                       LIMIT 1');
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    unset($_SESSION['old_username']);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    header("Location: index.php");
    exit;
} else {
    $_SESSION['old_username'] = $username;
    $_SESSION['error'] = "ユーザー名またはパスワードが違います";
    header("Location: login.php");
    exit;
}
