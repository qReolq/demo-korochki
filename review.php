<?php
require 'config.php';
require 'header.php';

$app_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT a.id, c.name FROM applications a JOIN courses c ON a.course_id = c.id WHERE a.id = ? AND a.user_id = ? AND a.status = 'Обучение завершено'");
$stmt->bind_param("ii", $app_id, $user_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    echo "<div class='alert alert-danger'>Неверная заявка.</div>";
    require 'footer.php';
    exit();
}
$stmt->bind_result($a_id, $course_name);
$stmt->fetch();

$check = $mysqli->prepare("SELECT id FROM reviews WHERE application_id = ?");
$check->bind_param("i", $app_id);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo "<div class='alert alert-info'>Отзыв уже оставлен.</div>";
    require 'footer.php';
    exit();
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review = trim($_POST['review']);
    $rating = (int)$_POST['rating'];
    if ($review === '' || $rating < 1 || $rating > 5) {
        $msg = "<div class='alert alert-danger'>Заполните текст и оценку от 1 до 5.</div>";
    } else {
        $ins = $mysqli->prepare("INSERT INTO reviews (application_id, user_id, review, rating) VALUES (?, ?, ?, ?)");
        $ins->bind_param("iisi", $app_id, $user_id, $review, $rating);
        if ($ins->execute()) {
            $msg = "<div class='alert alert-success'>Отзыв сохранен.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Ошибка: {$ins->error}</div>";
        }
    }
}
?>
<?= $msg ?>
<h2 class="mb-3">Отзыв о курсе <?= htmlspecialchars($course_name) ?></h2>
<form method="POST" class="card p-4">
    <div class="mb-3">
        <textarea name="review" class="form-control" rows="5" required></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Оценка</label>
        <select name="rating" class="form-select" required>
            <option value="">Выберите</option>
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Сохранить отзыв</button>
</form>
<?php require 'footer.php'; ?>
