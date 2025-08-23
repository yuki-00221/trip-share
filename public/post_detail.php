<?php
    session_start();
    require_once __DIR__ . '/../config/db_connect.php';

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header('Location: index.php');
        exit;
    }

    $post_id = (int)$_GET['id'];

    $stmt = $pdo->prepare('
        SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.id = ?
        LIMIT 1
    ');
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();

    if (!$post) {
        echo '投稿が見つかりません';
        exit;
    }

    include 'templates/header.php';
?>

<h2 class="back-h2">
  <a href="javascript:history.back()">←</a> ポスト
</h2>

<div class="post-detail">
    <h1><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="post-meta">
        投稿者: <?= htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') ?> |
        都道府県: <?= htmlspecialchars($post['prefecture'], ENT_QUOTES, 'UTF-8') ?> |
        旅行日: <?= htmlspecialchars($post['travel_date'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <?php if ($post['image_path']): ?>
        <div class="post-image">
            <img src="<?= htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="投稿画像">
        </div>
    <?php endif; ?>

    <div class="post-body">
        <p><?= nl2br(htmlspecialchars($post['body'], ENT_QUOTES, 'UTF-8')) ?></p>
    </div>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
        <div class="post-actions">
            <a href="post_form.php?id=<?= $post['id'] ?>" class="btn btn-edit">編集</a>
            <a href="post_delete.php?id=<?= $post['id'] ?>" class="btn btn-delete" onclick="return confirm('本当に削除しますか？')">削除</a>
        </div>
    <?php endif; ?>
</div>


<?php
$back_url = $_SERVER['HTTP_REFERER'] ?? 'index.php';
?>

<?php include 'templates/footer.php'; ?>