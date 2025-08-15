<?php
    session_start();
    require_once '../config/db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if ($username === '' || $password === '' || $password_confirm === '') {
            $_SESSION['error'] = '全ての項目を入力してください';
        } elseif ($password !== $password_confirm) {
            $_SESSION['error'] = 'パスワードが一致しません';
        } else {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'そのユーザー名は既に使われています';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
                $stmt->execute();

                $_SESSION['success'] = '登録が完了しました。ログインしてください';
                header('Location: login.php');
                exit;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ユーザー登録 - TripShare</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <h1>ユーザー登録</h1>

        <?php
            if(isset($_SESSION['error'])) {
                echo '<p style="color:red">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>

        <form action="register.php" method="post">
            <label>ユーザー名:<br>
                <input type="text" name="username" required>
            </label><br><br>

            <label>パスワード:<br>
                <input type="password" name="password" required>
            </label><br><br>

            <label>パスワード確認:<br>
                <input type="password" name="password_confirm" required>
            </label><br><br>

            <button type="submit">登録</button>
        </form>

        <p>既にアカウントをお持ちですか？ <a href="login.php">ログイン</a></p>
    </body>
</html>