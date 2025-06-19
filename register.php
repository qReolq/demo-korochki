<?php require 'config.php'; require 'header.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fio = $_POST['fio'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (mb_strlen($login) < 6 || mb_strlen($_POST['password']) < 6) {
        echo "<div class='alert alert-danger'>Логин и пароль должны быть не менее 6 символов.</div>";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO users (fio, phone, email, login, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fio, $phone, $email, $login, $password);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Регистрация успешна. <a href='login.php'>Войти</a></div>";
        } else {
            echo "<div class='alert alert-danger'>Ошибка: {$stmt->error}</div>";
        }
    }
}
?>
<form method="POST" class="card p-4">
    <h2 class="mb-3">Регистрация</h2>
    <div class="mb-3"><label class="form-label">ФИО</label><input type="text" name="fio" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Телефон</label><input type="text" name="phone" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Логин</label><input type="text" name="login" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Пароль</label><input type="password" name="password" class="form-control" required></div>
    <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
</form>
<?php require 'footer.php'; ?>
