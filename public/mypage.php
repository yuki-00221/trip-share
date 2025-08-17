<?php
    session_start();
    require_once __DIR__ . '/../config/db_connect.php';

    if(!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
    $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();

    $stmt = $pdo->prepare('SELECT * FROM posts WHERE user_id = :uid ORDER BY created_at DESC');
    $stmt->bindValue(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll();
?>

<?php include 'templates/nav.php'; ?>

<h2>マイページ</h2>

<p>ようこそ、<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?> さん！</p>

<a href="post_form.php">＋ 新規投稿</a>

<h3>あなたの投稿一覧</h3>

<?php if($posts): ?>
    <ul>
        <?php foreach($posts as $post): ?>
            <li>
                <a href="post_detail.php?id=<?= $post['id'] ?>">
                    <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
                </a>
                （<?= htmlspecialchars($post['travel_date'], ENT_QUOTES, 'UTF-8') ?>）
                |
                <a href="post_form.php?id=<?= $post['id'] ?>">編集</a>
                |
                <a href="post_delete.php?id=<?= $post['id'] ?>" onclick="return confirm('本当に削除しますか？');">削除</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>まだ投稿がありません。</p>
<?php endif; ?>

<p><a href="index.php">← 投稿一覧へ戻る</a></p>