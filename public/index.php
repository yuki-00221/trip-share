<?php
    session_start();

    if(!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once __DIR__ . '/../config/db_connect.php';
    $stmt = $pdo->query("SELECT posts.*, users.username 
                        FROM posts 
                        JOIN users ON posts.user_id = users.id 
                        ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();
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

        <?php if(isset($_SESSION['user_id'])): ?>
            <p><a href="post_form.php" class="btn">新規投稿</a></p>
        <?php endif; ?>

        <h2>投稿一覧</h2>

        <?php if ($posts): ?>
            <ul>
                <?php foreach ($posts as $post): ?>
                    <li>
                        <h2><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <p>投稿者: <?= htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p>都道府県: <?= htmlspecialchars($post['prefecture'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p>旅行日: <?= htmlspecialchars($post['travel_date'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php if ($post['image_path']): ?>
                            <img src="<?= htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') ?>" width="200">
                        <?php endif; ?>
                        <p><?= nl2br(htmlspecialchars(mb_substr($post['body'], 0, 100), ENT_QUOTES, 'UTF-8')) ?>...</p>
                        <a href="post_detail.php?id=<?= $post['id'] ?>">続きを読む</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>まだ投稿はありません。</p>
        <?php endif; ?>
    </body>
</html>