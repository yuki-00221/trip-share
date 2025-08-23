<?php
function render_post_list(array $posts): void
{
    if (!$posts) {
        echo '<p>まだ投稿はありません。</p>';
        return;
    }
    echo '<div class="post-list">';
    foreach ($posts as $post) {
        echo '<a href="post_detail.php?id=' . htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8') . '" class="post-card-link">';
        echo '<div class="post-card">';

        if (!empty($post['image_path'])) {
            echo '<img src="' . htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') . '">';
        }

        echo '<h3>' . htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') . '</h3>';
        echo '<p class="meta">投稿者: ' . htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p class="meta">都道府県: ' . htmlspecialchars($post['prefecture'], ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p class="meta">旅行日: ' . htmlspecialchars($post['travel_date'], ENT_QUOTES, 'UTF-8') . '</p>';

        $body = htmlspecialchars($post['body'], ENT_QUOTES, 'UTF-8');
        $short_body = mb_substr($body, 0, 30);
        if (mb_strlen($body) > 30) {
            $short_body .= '...';
        }
        echo '<p>' . nl2br($short_body) . '</p>';

        echo '</div>';
        echo '</a>';
    }
    echo '</div>';
}

function get_prefectures(): array
{
    return [
        1 => "北海道",
        2 => "青森県",
        3 => "岩手県",
        4 => "宮城県",
        5 => "秋田県",
        6 => "山形県",
        7 => "福島県",
        8 => "茨城県",
        9 => "栃木県",
        10 => "群馬県",
        11 => "埼玉県",
        12 => "千葉県",
        13 => "東京都",
        14 => "神奈川県",
        15 => "新潟県",
        16 => "富山県",
        17 => "石川県",
        18 => "福井県",
        19 => "山梨県",
        20 => "長野県",
        21 => "岐阜県",
        22 => "静岡県",
        23 => "愛知県",
        24 => "三重県",
        25 => "滋賀県",
        26 => "京都府",
        27 => "大阪府",
        28 => "兵庫県",
        29 => "奈良県",
        30 => "和歌山県",
        31 => "鳥取県",
        32 => "島根県",
        33 => "岡山県",
        34 => "広島県",
        35 => "山口県",
        36 => "徳島県",
        37 => "香川県",
        38 => "愛媛県",
        39 => "高知県",
        40 => "福岡県",
        41 => "佐賀県",
        42 => "長崎県",
        43 => "熊本県",
        44 => "大分県",
        45 => "宮崎県",
        46 => "鹿児島県",
        47 => "沖縄県"
    ];
}

function get_prefecture_name(int $id): string
{
    $prefs = get_prefectures();
    return $prefs[$id] ?? '';
}

function get_prefecture_id(string $name): ?int
{
    $prefs = get_prefectures();
    $flip = array_flip($prefs);
    return $flip[$name] ?? null;
}
