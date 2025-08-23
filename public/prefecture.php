<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/functions.php';

$pref_code = isset($_GET['pref']) ? intval($_GET['pref']) : 0;
$pref_name = get_prefecture_name($pref_code);

require_once __DIR__ . '/../config/db_connect.php';
if ($pref_name) {
    $stmt = $pdo->prepare('SELECT posts.*, users.username
                           FROM posts
                           JOIN users ON posts.user_id = users.id
                           WHERE posts.user_id = :uid AND posts.prefecture = :prefecture
                           ORDER BY posts.created_at DESC');
    $stmt->bindValue(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':prefecture', $pref_name, PDO::PARAM_STR);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $posts = [];
}
include 'templates/header.php';
?>

<!-- Here starts the display section -->
<h2 class="back-h2">
    <a href="javascript:history.back()">←</a> <?php echo htmlspecialchars($pref_name ?: '都道府県'); ?>の投稿一覧
</h2>
<?=render_post_list($posts);?>

<?php include 'templates/footer.php'; ?>