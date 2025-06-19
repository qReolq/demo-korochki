<?php
require 'config.php';
require 'header.php';

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare(
    "SELECT c.name, c.description, c.image_url, a.id AS app_id, a.start_date, " .
    "a.payment_method, a.status, r.id AS review_id " .
    "FROM applications a " .
    "JOIN courses c ON a.course_id = c.id " .
    "LEFT JOIN reviews r ON r.application_id = a.id " .
    "WHERE a.user_id = ?"
);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2 class='mb-4'>Ваши заявки</h2>
<div class='row'>
<?php while ($row = $result->fetch_assoc()): ?>
    <div class='col-md-4 mb-4'>
        <div class='card'>
            <img src='<?= htmlspecialchars($row['image_url']) ?>' class='card-img-top' alt='Курс'>
            <div class='card-body'>
                <h5 class='card-title'><?= htmlspecialchars($row['name']) ?></h5>
                <p class='card-text'><?= htmlspecialchars($row['description']) ?></p>
                <p class='card-text'>
                    <small>Дата начала: <?= htmlspecialchars($row['start_date']) ?> | Оплата: <?= htmlspecialchars($row['payment_method']) ?></small>
                </p>
                <span class='badge bg-info'><?= htmlspecialchars($row['status']) ?></span>
                <?php if ($row['status'] == 'Обучение завершено'): ?>
                    <?php if ($row['review_id']): ?>
                        <p class='text-success mt-2'>Отзыв оставлен</p>
                    <?php else: ?>
                        <a href='review.php?id=<?= $row['app_id'] ?>' class='btn btn-sm btn-outline-primary mt-2'>Оставить отзыв</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>
<?php require 'footer.php'; ?>
