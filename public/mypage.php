<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/functions.php';

require_once __DIR__ . '/../config/db_connect.php';
// get user information
$stmt = $pdo->prepare('SELECT *
                       FROM users
                       WHERE id = :id
                       LIMIT 1');
$stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch();

// get user's posts
$stmt = $pdo->prepare('SELECT posts.*, users.username 
                       FROM posts
                       JOIN users ON posts.user_id = users.id
                       WHERE posts.user_id = :uid
                       ORDER BY posts.created_at DESC');
$stmt->bindValue(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

// get prefectures visited by users
$stmt = $pdo->prepare('SELECT DISTINCT prefecture
                       FROM posts
                       WHERE user_id = :uid');
$stmt->bindValue(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$pref_names = $stmt->fetchAll(PDO::FETCH_COLUMN);
$visited_prefs = array_map('get_prefecture_id', $pref_names);
$visited_prefs = array_filter($visited_prefs);

function extra_head()
{
    echo <<<EOT
        <script type="text/javascript" src="https://unpkg.com/japan-map-js@1.0.1/dist/jpmap.min.js"></script>
        <script type="text/javascript" src="dist/jpmap.min.js"></script>
    EOT;
}

include 'templates/header.php';
?>
<!-- Here starts the display section -->

<h2><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>のマイページ</h2>

<h3>訪問した都道府県</h3>
<div id="japan-map" class="map-container"></div>

<script>
    window.visitedPrefs = <?php echo json_encode($visited_prefs, JSON_NUMERIC_CHECK); ?>;
</script>
<script src="assets/js/japan-map-init.js"></script>

<h3>あなたの投稿一覧</h3>
<?= render_post_list($posts); ?>

<div class="new-post-btn">
    <a href="post_form.php" class="fab-btn">＋</a>
</div>

<?php include 'templates/footer.php'; ?>