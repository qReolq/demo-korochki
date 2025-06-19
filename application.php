<?php
require 'config.php';
require 'header.php';

$msg = '';
$selected = isset($_GET['course']) ? (int)$_GET['course'] : 0;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = $_POST['course_id'];
    $start_date = $_POST['start_date'];
    $payment_method = $_POST['payment_method'];

    $stmt = $mysqli->prepare("INSERT INTO applications (user_id, course_id, start_date, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $_SESSION['user_id'], $course_id, $start_date, $payment_method);

    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>Заявка успешно отправлена.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Ошибка: {$stmt->error}</div>";
    }
}

$courses = $mysqli->query("SELECT * FROM courses");
?>
<?= $msg ?>
<form method="POST" class="card p-4 mb-4">
    <h2 class="mb-3">Новая заявка</h2>
    <div class="mb-3"><label class="form-label">Курс</label>
    <select name="course_id" class="form-select">
    <?php while ($c = $courses->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>" <?= $selected == $c['id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
    <?php endwhile; ?>
    </select>
    </div>
    <div class="mb-3"><label class="form-label">Дата начала</label>
    <input type="date" name="start_date" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Способ оплаты</label>
    <select name="payment_method" class="form-select">
        <option>Наличные</option>
        <option>Банковский перевод</option>
    </select></div>
    <button type="submit" class="btn btn-primary w-100">Отправить заявку</button>
</form>
<?php require "footer.php"; ?>
