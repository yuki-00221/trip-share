<?php
    session_start();
    if(isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ログイン - TripShare</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <h2>ログイン</h2>
        <?php
            if(isset($_SESSION['error'])) {
                echo '<p style="color:red">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>
        <form action="login_process.php" method="post">
            <label for="username">ユーザー名:</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($_SESSION['old_username'] ?? '') ?>" required>
            <br>
            <label for="password">パスワード:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <button type="submit">ログイン</button>
        </form>
        <p>まだアカウントを持っていない場合は <a href="register.php">登録</a></p>
    </body>
</html>