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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <div class="post-list">
                <?php foreach($posts as $post): ?>
                    <a href="post_detail.php?id=<?= $post['id'] ?>" class="post-card-link">
                        <div class="post-card">
                            <?php if ($post['image_path']): ?>
                                <img src="<?= htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>">
                            <?php endif; ?>
                            <h3><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="meta">投稿者: <?= htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="meta">都道府県: <?= htmlspecialchars($post['prefecture'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="meta">旅行日: <?= htmlspecialchars($post['travel_date'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php 
                                $body = htmlspecialchars($post['body'], ENT_QUOTES, 'UTF-8');
                                $short_body = mb_substr($body, 0, 30);
                                if (mb_strlen($body) > 30) {
                                    $short_body .= '...';
                                }
                            ?>
                            <p><?= nl2br($short_body) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>まだ投稿はありません。</p>
        <?php endif; ?>
    </body>
</html>