<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/functions.php';

require_once __DIR__ . '/../config/db_connect.php';
$stmt = $pdo->query('SELECT posts.*, users.username 
                     FROM posts 
                     JOIN users ON posts.user_id = users.id 
                     ORDER BY created_at DESC');
$posts = $stmt->fetchAll();

include 'templates/header.php';
?>

<!-- Here starts the display section -->
<h2>投稿一覧</h2>
<?=render_post_list($posts);?>

<div class="new-post-btn">
    <a href="post_form.php" class="fab-btn">＋</a>
</div>

<?php include 'templates/footer.php'; ?>