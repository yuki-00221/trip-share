<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'templates/header.php';
?>

<!-- Here starts the display section -->
<h2>ログイン</h2>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<p style="color:green">' . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . '</p>';
    unset($_SESSION['success']);
}
?>

<form action="login_process.php" method="post">
    <label for="username">ユーザー名：</label>
    <input type="text" name="username" id="username" value="<?= htmlspecialchars($_SESSION['old_username'] ?? '') ?>" required>
    <br>
    <label for="password">パスワード：</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit">ログイン</button>
</form>
<p>まだアカウントを持っていない場合は <a href="register.php">登録</a></p>

<?php include 'templates/footer.php'; ?>