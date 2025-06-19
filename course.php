<?php
require 'config.php';
require 'header.php';

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $mysqli->prepare("SELECT name, description, image_url FROM courses WHERE id = ?");
$stmt->bind_param('i', $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();
if (!$course) {
    echo "<div class='alert alert-danger'>Курс не найден.</div>";
    require 'footer.php';
    exit();
}

$rev = $mysqli->prepare("SELECT r.review, r.rating, r.created_at, u.fio FROM reviews r JOIN applications a ON r.application_id = a.id JOIN users u ON r.user_id = u.id WHERE a.course_id = ? ORDER BY r.created_at DESC");
$rev->bind_param('i', $course_id);
$rev->execute();
$reviews = $rev->get_result();

$avg_res = $mysqli->prepare("SELECT AVG(r.rating) AS avg_rating FROM reviews r JOIN applications a ON r.application_id = a.id WHERE a.course_id = ?");
$avg_res->bind_param('i', $course_id);
$avg_res->execute();
$avg = $avg_res->get_result()->fetch_assoc();
$avg_rating = $avg && $avg['avg_rating'] ? round($avg['avg_rating'], 1) : null;
?>
<h2 class="mb-4"><?= htmlspecialchars($course['name']) ?></h2>
<img src="<?= htmlspecialchars($course['image_url']) ?>" class="img-fluid mb-3" alt="Курс">
<p><?= nl2br(htmlspecialchars($course['description'])) ?></p>
<?php if ($avg_rating): ?>
<p><strong>Средняя оценка:</strong> <?= $avg_rating ?></p>
<?php endif; ?>
<h4 class="mt-4">Отзывы</h4>
<?php if ($reviews->num_rows === 0): ?>
<p>Пока нет отзывов.</p>
<?php else: ?>
<?php while ($r = $reviews->fetch_assoc()): ?>
<div class="card mb-3">
    <div class="card-body">
        <h6 class="card-subtitle mb-2 text-muted">Оценка: <?= $r['rating'] ?> | <?= htmlspecialchars($r['fio']) ?> | <?= $r['created_at'] ?></h6>
        <p class="card-text"><?= nl2br(htmlspecialchars($r['review'])) ?></p>
    </div>
</div>
<?php endwhile; ?>
<?php endif; ?>
<?php require 'footer.php'; ?>
