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
?>

<?php include __DIR__ . '/templates/nav.php'; ?>

<h1><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h1>
<p>投稿者: <?= htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') ?></p>
<p>都道府県: <?= htmlspecialchars($post['prefecture'], ENT_QUOTES, 'UTF-8') ?></p>
<p>旅行日: <?= htmlspecialchars($post['travel_date'], ENT_QUOTES, 'UTF-8') ?></p>
<p>投稿日: <?= htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8') ?></p>

<?php if ($post['image_path']): ?>
    <img src="<?= htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') ?>" width="400">
<?php endif; ?>

<p><?= nl2br(htmlspecialchars($post['body'], ENT_QUOTES, 'UTF-8')) ?></p>

<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $post['user_id']): ?>
    <p>
        <a href="post_form.php?id=<?= $post['id'] ?>" class="btn">編集</a>
        <a href="post_delete.php?id=<?= $post['id'] ?>" class="btn" onclick="return confirm('本当に削除しますか？')">削除</a>
    </p>
<?php endif; ?>

<p><a href="index.php">一覧に戻る</a></p>
