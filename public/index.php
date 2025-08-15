<?php
    session_start();

    if(!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>TripShare</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <?php include 'templates/nav.php'; ?>

        <h1>投稿一覧</h1>
        <!-- 投稿内容など -->
    </body>
</html>