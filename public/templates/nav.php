<nav class="site-nav">
    <div class="nav-header">
        <h1><a href="index.php">TripShare</a></h1>
        <button class="nav-toggle" id="nav-toggle">☰</button>
    </div>
    <ul class="nav-menu" id="nav-menu">
        <li><a href="index.php">投稿一覧</a></li>
        <li><a href="mypage.php">マイページ</a></li>
        <li><a href="post_form.php">新規投稿</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="logout.php">ログアウト</a></li>
        <?php else: ?>
            <li><a href="login.php">ログイン</a></li>
            <li><a href="register.php">ユーザー登録</a></li>
        <?php endif; ?>
    </ul>
</nav>
