<?php
require 'config.php';

$msg = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fio = $_POST['fio'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (mb_strlen($login) < 6 || !preg_match('/^[\x{0400}-\x{04FF}]+$/u', $login)) {
        $msg = "<div class='alert alert-danger'>Логин должен быть не менее 6 символов и содержать только кириллицу.</div>";
    } elseif (mb_strlen($_POST['password']) < 6) {
        $msg = "<div class='alert alert-danger'>Пароль должен быть не менее 6 символов.</div>";
    } else {
        $check = $mysqli->prepare("SELECT id FROM users WHERE login = ?");
        $check->bind_param("s", $login);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $msg = "<div class='alert alert-danger'>Логин уже существует.</div>";
        } else {
            $stmt = $mysqli->prepare("INSERT INTO users (fio, phone, email, login, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fio, $phone, $email, $login, $password);

            if ($stmt->execute()) {
                $msg = "<div class='alert alert-success'>Регистрация успешна. <a href='login.php'>Войти</a></div>";
            } else {
                $msg = "<div class='alert alert-danger'>Ошибка: {$stmt->error}</div>";
            }
        }
    }
}
?>
<?php require 'header.php'; ?>
<?= $msg ?>
<form method="POST" class="card p-4">
    <h2 class="mb-3">Регистрация</h2>
    <div class="mb-3"><label class="form-label">ФИО</label><input type="text" name="fio" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Телефон</label><input type="text" name="phone" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Логин</label><input type="text" name="login" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Пароль</label><input type="password" name="password" class="form-control" required></div>
    <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
</form>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const phone = document.querySelector('input[name="phone"]');
  if (phone) {
    phone.addEventListener('input', () => {
      let digits = phone.value.replace(/\D/g, '').substring(0, 10);
      let result = '+7(';
      if (digits.length >= 1) result += digits.substring(0, 3);
      if (digits.length >= 4) result += ')-' + digits.substring(3, 6);
      if (digits.length >= 7) result += '-' + digits.substring(6, 8);
      if (digits.length >= 9) result += '-' + digits.substring(8, 10);
      phone.value = result;
    });
  }
});
</script>
<?php require 'footer.php'; ?>
