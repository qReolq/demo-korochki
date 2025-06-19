<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

$msg = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password, is_admin FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->bind_result($user_id, $hash, $is_admin);
    $stmt->fetch();

    if ($user_id && $password === $hash) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['is_admin'] = $is_admin;
        $target = $is_admin ? 'admin.php' : 'dashboard.php';
        header("Location: $target");
        exit();
    } else {
        $msg = "<div class='alert alert-danger'>Неверный логин или пароль.</div>";
    }
}
?>
<?php require 'header.php'; ?>
<?= $msg ?>
<form method="POST" class="card p-4">
    <h2 class="mb-3">Вход</h2>
    <div class="mb-3"><label class="form-label">Логин</label><input type="text" name="login" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Пароль</label><input type="password" name="password" class="form-control" required></div>
    <button type="submit" class="btn btn-primary w-100">Войти</button>
</form>
<p class="text-center mt-3">Нет аккаунта? <a href="register.php">Регистрация</a></p>
<?php require 'footer.php'; ?>
