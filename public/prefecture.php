<?php
require_once('../config/db_connect.php');

$pref_code = isset($_GET['pref']) ? intval($_GET['pref']) : 0;

// 県コード → 県名に変換するマップ
$pref_map = [
    1=>'北海道', 2=>'青森県', 3=>'岩手県', 4=>'宮城県', 5=>'秋田県', 
    6=>'山形県', 7=>'福島県', 8=>'茨城県', 9=>'栃木県', 10=>'群馬県',
    11=>'埼玉県', 12=>'千葉県', 13=>'東京都', 14=>'神奈川県', 15=>'新潟県',
    16=>'富山県', 17=>'石川県', 18=>'福井県', 19=>'山梨県', 20=>'長野県',
    21=>'岐阜県', 22=>'静岡県', 23=>'愛知県', 24=>'三重県', 25=>'滋賀県',
    26=>'京都府', 27=>'大阪府', 28=>'兵庫県', 29=>'奈良県', 30=>'和歌山県',
    31=>'鳥取県', 32=>'島根県', 33=>'岡山県', 34=>'広島県', 35=>'山口県',
    36=>'徳島県', 37=>'香川県', 38=>'愛媛県', 39=>'高知県', 40=>'福岡県',
    41=>'佐賀県', 42=>'長崎県', 43=>'熊本県', 44=>'大分県', 45=>'宮崎県',
    46=>'鹿児島県', 47=>'沖縄県'
];

$pref_name = isset($pref_map[$pref_code]) ? $pref_map[$pref_code] : '';

if ($pref_name) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE prefecture = ? ORDER BY travel_date DESC");
    $stmt->execute([$pref_name]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $posts = [];
}
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title><?php echo htmlspecialchars($pref_name ?: '都道府県'); ?>の投稿 - TripShare</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>

        <?php include 'templates/nav.php'; ?>

        <h2><?php echo htmlspecialchars($pref_name ?: '都道府県'); ?>の投稿一覧</h2>

        <?php if($posts !== []): ?>
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

        <p><a href="mypage.php">← マイページへ戻る</a></p>
    </body>
</html>