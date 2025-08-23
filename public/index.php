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

    include 'templates/header.php';
?>

<div class="new-post-btn">
  <a href="post_form.php" class="fab-btn">＋</a>
</div>

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

<?php include 'templates/footer.php'; ?>