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

    $stmt = $pdo->prepare('SELECT posts.*, users.username 
                            FROM posts
                            JOIN users ON posts.user_id = users.id
                            WHERE posts.user_id = :uid
                            ORDER BY posts.created_at DESC
                            ');
    $stmt->bindValue(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll();

    $stmt = $pdo->prepare('SELECT DISTINCT prefecture FROM posts WHERE user_id = :uid');
    $stmt->bindValue(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $pref_names = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $pref_map = [
        "北海道" => 1, "青森県" => 2, "岩手県" => 3, "宮城県" => 4, "秋田県" => 5, "山形県" => 6, "福島県" => 7,
        "茨城県" => 8, "栃木県" => 9, "群馬県" => 10, "埼玉県" => 11, "千葉県" => 12, "東京都" => 13, "神奈川県" => 14,
        "新潟県" => 15, "富山県" => 16, "石川県" => 17, "福井県" => 18, "山梨県" => 19, "長野県" => 20, "岐阜県" => 21, "静岡県" => 22, "愛知県" => 23,
        "三重県" => 24, "滋賀県" => 25, "京都府" => 26, "大阪府" => 27, "兵庫県" => 28, "奈良県" => 29, "和歌山県" => 30,
        "鳥取県" => 31, "島根県" => 32, "岡山県" => 33, "広島県" => 34, "山口県" => 35,
        "徳島県" => 36, "香川県" => 37, "愛媛県" => 38, "高知県" => 39,
        "福岡県" => 40, "佐賀県" => 41, "長崎県" => 42, "熊本県" => 43, "大分県" => 44, "宮崎県" => 45, "鹿児島県" => 46, "沖縄県" => 47
    ];


    $visited_prefs = [];
    foreach ($pref_names as $name) {
        if (isset($pref_map[$name])) {
            $visited_prefs[] = $pref_map[$name];
        }
    }
    function extra_head() {
?>
        <script type="text/javascript" src="https://unpkg.com/japan-map-js@1.0.1/dist/jpmap.min.js"></script>
        <script type="text/javascript" src="dist/jpmap.min.js"></script>
<?php
    }

    include 'templates/header.php';
?>

<h2>マイページ</h2>

<p>ようこそ、<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?> さん！</p>

<div class="new-post-btn">
  <a href="post_form.php" class="fab-btn">＋</a>
</div>

<h3>訪問した都道府県</h3>
<div id="japan-map" class="map-container"></div>

<script>
    var visitedPrefs = <?php echo json_encode($visited_prefs); ?>;
    var allPrefs = Array.from({length: 47}, (_, i) => i + 1);

    var areas = allPrefs.map(function(code) {
        return {
            code: code,
            color: visitedPrefs.includes(code) ? "#4CAF50" : "#CCCCCC", // 緑 or グレー
            hoverColor: visitedPrefs.includes(code) ? "#66BB6A" : "#999999"
        };
    });

    var d = new jpmap.japanMap(document.getElementById("japan-map"), {
    showsPrefectureName: false, //都道府県名を表示させる
        width: document.getElementById("japan-map").offsetWidth,
    movesIslands: true, //沖縄地方が地図の左上の分離されたスペースに移動する
    lang: 'ja', //表示させている都道府県名を日本語にする
    areas: areas,
    onSelect: function(data){
        window.location.href = 'prefecture.php?pref=' + data.code;
    }
    });
</script>

<h3>あなたの投稿一覧</h3>

<?php if($posts): ?>
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