<?php
    session_start();
    require_once('../config/db_connect.php');

    $pref_code = isset($_GET['pref']) ? intval($_GET['pref']) : 0;

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
        $stmt = $pdo->prepare(
            'SELECT posts.*, users.username
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.user_id = :uid AND posts.prefecture = :prefecture
            ORDER BY posts.created_at DESC'
        );
        $stmt->bindValue(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':prefecture', $pref_name, PDO::PARAM_STR);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $posts = [];
    }
    include 'templates/header.php';
?>

<h2 class="back-h2">
  <a href="javascript:history.back()">←</a> <?php echo htmlspecialchars($pref_name ?: '都道府県'); ?>の投稿一覧
</h2>

<?php if($posts !== []): ?>
    <div class="post-list">
        <?php foreach($posts as $post): ?>
            <a href="post_detail.php?id=<?= $post['id'] ?>" class="post-card-link">
                <div class="post-card">
                    <?php if ($post['image_path']): ?>
                        <img src="<?= htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="meta">投稿者: <?= htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="meta">都道府県: <?= htmlspecialchars($post['prefecture'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="meta">旅行日: <?= htmlspecialchars($post['travel_date'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php 
                        $body = htmlspecialchars($post['body'], ENT_QUOTES, 'UTF-8');
                        $short_body = mb_substr($body, 0, 30);
                        if (mb_strlen($body) > 30) {
                            $short_body .= '...';
                        }
                    ?>
                    <p><?= nl2br($short_body) ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>まだ投稿がありません。</p>
<?php endif; ?>

<?php include 'templates/footer.php'; ?>