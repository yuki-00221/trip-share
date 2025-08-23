<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/functions.php';

$title = '';
$body = '';
$prefecture = '';
$travel_date = '';
$image_path = '';
$is_edit = false;

require_once __DIR__ . '/../config/db_connect.php';
if (isset($_GET['id'])) {
    $is_edit = true;
    $post_id = (int)$_GET['id'];

    $stmt = $pdo->prepare('SELECT *
                           FROM posts
                           WHERE id = :id AND user_id = :user_id
                           LIMIT 1');
    $stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch();

    if ($post) {
        $title = $post['title'];
        $body = $post['body'];
        $prefecture = $post['prefecture'];
        $travel_date = $post['travel_date'];
        $image_path = $post['image_path'];
    }
}

if (isset($_SESSION['old'])) {
    $title = $_SESSION['old']['title'] ?? $title;
    $body = $_SESSION['old']['body'] ?? $body;
    $prefecture = $_SESSION['old']['prefecture'] ?? $prefecture;
    $travel_date = $_SESSION['old']['travel_date'] ?? $travel_date;
    unset($_SESSION['old']);
}
include 'templates/header.php';
?>

<!-- Here starts the display section -->
<h2 class="back-h2">
    <a href="javascript:history.back()">←</a><?= $is_edit ? '投稿を編集する' : '新しい旅行記を投稿する' ?>
</h2>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}
?>

<form action="post_save.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $is_edit ? $post_id : '' ?>">

    <label>タイトル：</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($title) ?>">

    <label>本文：</label><br>
    <textarea name="body"><?= htmlspecialchars($body) ?></textarea>

    <label>都道府県：</label><br>
    <select name="prefecture" required>
        <option value="">選択してください</option>
        <?php
        $prefs = get_prefectures();
        foreach ($prefs as $name) {
            $selected = ($prefecture === $name) ? 'selected' : '';
            echo "<option value='$name' $selected>$name</option>";
        }
        ?>
    </select>

    <label>旅行日：</label><br>
    <input type="date" name="travel_date" value="<?= htmlspecialchars($travel_date) ?>">

    <label>写真：</label><br>
    <?php if ($is_edit && $image_path): ?>
        <img src="<?= htmlspecialchars($image_path) ?>" width="150"><br>
        <input type="checkbox" name="delete_image" value="1">既存画像を削除する<br>
        変更する場合は新しい画像をアップロードしてください
    <?php endif; ?>
    <input type="file" name="image" id="image-input">
    <span id="clear-image" class="btn-clear">選択解除</span><br><br>

    <script>
        document.getElementById('clear-image').addEventListener('click', function() {
            document.getElementById('image-input').value = '';
        });
    </script>

    <button type="submit"><?= $is_edit ? '更新する' : '投稿する' ?></button>
</form>

<?php include 'templates/footer.php'; ?>