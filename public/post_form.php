<?php
    session_start();

    if(!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once __DIR__ . '/../config/db_connect.php';

    $title = '';
    $body = '';
    $prefecture = '';
    $travel_date = '';
    $image_path = '';
    $is_edit = false;

    if(isset($_GET['id'])) {
        $is_edit = true;
        $post_id = (int)$_GET['id'];

        $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id AND user_id = :user_id LIMIT 1');
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

    if(isset($_SESSION['old'])) {
        $title = $_SESSION['old']['title'] ?? $title;
        $body = $_SESSION['old']['body'] ?? $body;
        $prefecture = $_SESSION['old']['prefecture'] ?? $prefecture;
        $travel_date = $_SESSION['old']['travel_date'] ?? $travel_date;
        unset($_SESSION['old']);
    }
    include 'templates/header.php';
?>

<div class="back-link">
  <a href="javascript:history.back()">←</a><?= $is_edit ? '投稿を編集する' : '新しい旅行記を投稿する' ?>
</div>

<?php
    if(isset($_SESSION['error'])) {
        echo '<p style="color:red">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }
?>

<form action="post_save.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $is_edit ? $post_id : '' ?>">

    <label>タイトル：</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($title) ?>"><br><br>

    <label>本文：</label><br>
    <textarea name="body"><?= htmlspecialchars($body) ?></textarea><br><br>

    <label>都道府県：</label><br>
    <select name="prefecture" required>
        <option value="">選択してください</option>
        <?php
            $prefs = [
                "北海道","青森県","岩手県","宮城県","秋田県","山形県","福島県",
                "茨城県","栃木県","群馬県","埼玉県","千葉県","東京都","神奈川県",
                "新潟県","富山県","石川県","福井県","山梨県","長野県",
                "岐阜県","静岡県","愛知県","三重県",
                "滋賀県","京都府","大阪府","兵庫県","奈良県","和歌山県",
                "鳥取県","島根県","岡山県","広島県","山口県",
                "徳島県","香川県","愛媛県","高知県",
                "福岡県","佐賀県","長崎県","熊本県","大分県","宮崎県","鹿児島県","沖縄県"
            ];
            foreach ($prefs as $pref) {
                $selected = ($prefecture === $pref) ? 'selected' : '';
                echo "<option value='$pref' $selected>$pref</option>";
            }
        ?>
    </select><br><br>

    <label>旅行日：</label><br>
    <input type="date" name="travel_date" value="<?= htmlspecialchars($travel_date) ?>"><br><br>

    <label>写真：</label><br>
    <?php if ($is_edit && $image_path): ?>
        <img src="<?= htmlspecialchars($image_path) ?>" width="150"><br>
        <small>変更する場合は新しい画像をアップロードしてください</small><br>
    <?php endif; ?>
    <input type="file" name="image"><br><br>

    <button type="submit"><?= $is_edit ? '更新する' : '投稿する' ?></button>
</form>

<?php include 'templates/footer.php'; ?>