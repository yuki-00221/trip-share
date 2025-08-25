<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/functions.php';
require_once __DIR__ . '/../config/db_connect.php';

$sort = $_GET['sort'] ?? 'created_at';
$allowed_sorts = ['created_at', 'travel_date'];

if (!in_array($sort, $allowed_sorts, true)) {
    $sort = 'created_at';
}

$search_user = $_GET['user'] ?? '';
$search_pref = $_GET['pref'] ?? '';
$search_keyword = $_GET['q'] ?? '';

$sql = "SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE 1=1";

$params = [];

if ($search_user !== '') {
    $sql .= " AND users.username LIKE :user";
    $params[':user'] = "%{$search_user}%";
}

if ($search_pref !== '') {
    $sql .= " AND posts.prefecture = :pref";
    $params[':pref'] = $search_pref;
}

if ($search_keyword !== '') {
    $sql .= " AND (posts.title LIKE :kw OR posts.body LIKE :kw)";
    $params[':kw'] = "%{$search_keyword}%";
}

$sql .= " ORDER BY posts.{$sort} DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

include 'templates/header.php';
?>

<!-- Here starts the display section -->
<h2>投稿一覧</h2>

<button type="button" class="search-toggle">
    検索 <span class="arrow">▼</span>
</button>

<form method="get" class="search-form <?= ($search_user || $search_pref || $search_keyword) ? 'open' : '' ?>">
    <input type="text" name="user" placeholder="ユーザー名"
           value="<?= htmlspecialchars($search_user) ?>">

    <select name="pref">
        <option value="">すべての都道府県</option>
        <?php
        $prefs = get_prefectures();
        foreach ($prefs as $name) {
            $selected = ($search_pref === $name) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($name) . "' $selected>$name</option>";
        }
        ?>
    </select>

    <input type="text" name="q" placeholder="タイトル・本文を検索"
           value="<?= htmlspecialchars($search_keyword) ?>">

    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    <button type="submit">検索</button>
</form>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.querySelector(".search-toggle");
    const form = document.querySelector(".search-form");
    const arrow = toggleBtn.querySelector(".arrow");

    if (form.classList.contains("open")) {
        arrow.textContent = "▲";
    }

    toggleBtn.addEventListener("click", () => {
        form.classList.toggle("open");
        arrow.textContent = form.classList.contains("open") ? "▲" : "▼";
    });
});
</script>

<div class="sort-options">
    <a href="?sort=created_at"
        class="<?= $sort === 'created_at' ? 'active' : '' ?>">投稿日順</a>
    <a href="?sort=travel_date"
        class="<?= $sort === 'travel_date' ? 'active' : '' ?>">旅行日順</a>
</div>

<?= render_post_list($posts); ?>

<div class="new-post-btn">
    <a href="post_form.php" class="fab-btn">＋</a>
</div>

<?php include 'templates/footer.php'; ?>